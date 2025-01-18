<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $type = $user->type;
        $id = $user->id;
        $users = User::with(['Profile', 'Roles'])->where(function($query) use ($request, $type, $id) {
            $query->when($type != 'admin', function($query) use($id) {
                $query->where('parent_id', $id);
            })->when($request->has('parent_id'), function ($query) use ($request) {
                $query->where('parent_id', $request['parent_id']);
            });
        })->orWhere('id', $id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $users,
            'message' => 'Users fetched successfully',
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $parentId = $request->has('parent_id') ? $request['parent_id'] : (auth()->check() ? auth()->id() : 0);
        $request->validate([
            'name' => 'required|max:255',
            'password' => 'required|confirmed|min:6|max:32',
            'phone_number' => [
                Rule::requiredIf(!$request->has('email')),
                ($request->has('phone_number') && !is_null($request['phone_number']) ? 'unique:users,phone_number' : ''),
            ],
            'email' => [Rule::requiredIf(!$request->has('phone_number')), 'email', 'unique:users,email'],
            'language' => ['nullable', Rule::in(array_keys(Translation::Languages))],
            'gender' => 'nullable|in:male,female',
            'first_name' => 'nullable|max:255',
            'last_name' => 'nullable|max:255',
            'birthday' => 'nullable|date',
            'address' => 'nullable',
            'roles' => 'nullable|exists:roles,id',
            'timezone' => ['required', Rule::in(timezone_identifiers_list())],
            'calendar' => ['required', Rule::in(User::Calendars)],
        ]);
        DB::beginTransaction();
        try {
            $user = new User();
            $user->parent_id = $parentId;
            $user->name = $request['name'];
            $user->email = $request->has('email') ? $request['email'] : null;
            $user->password = Hash::make($request['password']);
            $user->phone_number = $request->has('phone_number') ? $request['phone_number'] : null;
            $user->save();
            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->first_name = $request->has('first_name') && !is_null($request['first_name']) && $request['first_name'] != '' ? $request['first_name'] : null;
            $profile->last_name = $request->has('last_name') && !is_null($request['last_name']) && $request['last_name'] != '' ? $request['last_name'] : null;
            $profile->gender = $request->has('gender') && !is_null($request['gender']) && $request['gender'] != '' ? $request['gender'] : null;
            $profile->birthday = $request->has('birthday') && !is_null($request['birthday']) && $request['birthday'] != '' ? $request['birthday'] : null;
            $profile->address = $request->has('address') && !is_null($request['address']) && $request['address'] != '' ? $request['address'] : null;
            $profile->language = $request->has('language') && !is_null($request['language']) && $request['language'] != '' ? $request['language'] : 'en';
            $profile->calendar = $request->has('calendar') && !is_null($request['calendar']) && $request['calendar'] != '' ? $request['calendar'] : 'Gregorian';
            $profile->timezone = $request->has('timezone') && !is_null($request['timezone']) && $request['timezone'] != '' ? $request['timezone'] : 'Asia/Tehran';
            $profile->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $user,
                'message' => __('user.created'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'data' => $exception->getMessage(),
                'message' => 'Error while creating user',
            ], 500);
        }
    }

    public function show(User $user): JsonResponse
    {
        $user->load('Profile', 'Roles');
        $user->Profile->Translate();
        return response()->json([
            'status' => 'success',
            'data' => $user,
            'message' => 'User fetched successfully',
        ], 200);
    }

    public function update(User $user, Request $request): JsonResponse
    {
        $request->validate([
            'names' => 'required|max:255',
            'password' => 'nullable|confirmed|min:6|max:32',
            'phone_number' => $request->has('phone_number') && $request['phone_number'] == $user->phone_number ? '' : 'unique:users,phone_number',
            'email' => ['email', ($request->has('email') && $request['email'] == $user->email ? '' : 'unique:users,email')],
            'first_name' => 'nullable|max:255',
            'last_name' => 'nullable|max:255',
            'gender' => 'nullable|in:male,female',
            'birthday' => 'nullable|date',
            'address' => 'nullable',
            'roles' => 'nullable|exists:roles,id',
            'timezone' => ['required', Rule::in(timezone_identifiers_list())],
            'calendar' => ['required', Rule::in(User::Calendars)],
        ]);
        DB::beginTransaction();
        try {
            $parentId = $request->has('parent_id') ? $request['parent_id'] : $user->parent_id;
            $user->name = $request['names'];
            if ($request->has('password'))
                $user->password = Hash::make($request['password']);
            if ($request->has('phone_number') && $request['phone_number'] != $user->phone_number) {
                $user->phone_number = $request['phone_number'];
                $user->phone_number_verified_at = null;
            }
            if ($request->has('email') && $request['email'] != $user->email) {
                $user->email = $request['email'];
                $user->email_verified_at = null;
            }
            $user->parent_id = $parentId;
            $user->save();
            $profile = $user->Profile;
            $profile->first_name = $request->has('first_name') ? $request['first_name'] : $profile->first_name;
            $profile->last_name = $request->has('last_name') ? $request['last_name'] : $profile->last_name;
            $profile->gender = $request->has('gender') ? $request['gender'] : $profile->gender;
            $profile->birthday = $request->has('birthday') ? $request['birthday'] : $profile->birthday;
            $profile->address = $request->has('address') ? $request['address'] : $profile->address;
            $profile->language = $request->has('language') ? $request['language'] : $profile->language;
            $profile->calendar = $request->has('calendar') ? $request['calendar'] : $profile->calendar;
            $profile->timezone = $request->has('timezone') ? $request['timezone'] : $profile->timezone;
            $profile->save();
            DB::commit();
            $user->Profile->Translate();
            return response()->json([
                'status' => 'success',
                'data' => $user,
                'message' => __('user.updated'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'data' => $exception->getMessage(),
                'message' => 'Error while updating user',
            ], 500);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user->Profile()->delete();
            $user->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => __('user.deleted'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'data' => $exception->getMessage(),
                'message' => 'User deleted successfully',
            ], 500);
        }
    }

    public function translations()
    {
        $translations = Translation::where('user_id', auth()->id())->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $translations,
        ], 200);
    }

    public function translate(Translation $translation, Request $request)
    {
        $request->validate([
            'translation' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $translation->update([
                'value' => $request['translation'],
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $translation,
                'message' => __('user.translation.updated'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function roles(User $user)
    {
        $roles = $user->Roles;
        return response()->json([
            'status' => 'success',
            'data' => $roles,
        ], 200);
    }

    public function set(User $user, Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'roles.*' => 'required|exists:roles,id',
            ]);
            $user->Roles()->sync($request['roles']);
            DB::commit();
            $roles = $user->Roles;
            return response()->json([
                'status' => 'success',
                'data' => $roles,
                'message' => __('user.roles.updated'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function languages()
    {
        $languages = [
            'fa' => 'Persian',
            'en' => 'English',
            'ar' => 'Arabic',
            'tr' => 'Turkish',
        ];
        return response()->json([
            'status' => 'success',
            'data' => $languages,
            'message' => 'Languages fetched successfully',
        ], 200);
    }

    public function timezones()
    {
        return response()->json([
            'status' => 'success',
            'data' => timezone_identifiers_list(),
            'message' => 'Timezones fetched successfully',
        ], 200);
    }
}
