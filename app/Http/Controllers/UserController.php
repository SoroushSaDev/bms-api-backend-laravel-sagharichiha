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
        $request->validate([
            'names' => 'required|max:255',
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
        $parentId = $request->has('parent_id') ? $request['parent_id'] : (auth()->check() ? auth()->id() : 0);
        DB::beginTransaction();
        try {
            $user = User::create([
                'parent_id' => $parentId,
                'name' => $request['names'],
                'password' => Hash::make($request['password']),
                'email' => $request->has('email') ? $request['email'] : null,
                'phone_number' => $request->has('phone_number') ? $request['phone_number'] : null,
            ]);
            Profile::create([
                'user_id' => $user->id,
                'first_name' => RequestHas($request, 'first_name') ? $request['first_name'] : null,
                'last_name' => RequestHas($request, 'last_name') ? $request['last_name'] : null,
                'gender' => RequestHas($request, 'gender') ? $request['gender'] : null,
                'birthday' => RequestHas($request, 'birthday') ? $request['birthday'] : null,
                'address' => RequestHas($request, 'address') ? $request['address'] : null,
                'language' => RequestHas($request, 'language') ? $request['language'] : 'en',
                'calendar' => RequestHas($request, 'calendar') ? $request['calendar'] : 'Gregorian',
                'timezone' => RequestHas($request, 'timezone') ? $request['timezone'] : 'Asia/Tehran',
            ]);
            $user->load('Profile');
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
            'name' => 'required|max:255',
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
            $user->update([
                'parent_id' => $parentId,
                'name' => $request['name'],
                'password' => RequestHas($request, 'password') ? Hash::make($request['password']) : $user->password,
                'phone_number' => RequestHas($request, 'phone_number') ? $request['phone_number'] : $user->phone_number,
                'phone_number_verified_at' => RequestHas($request, 'phone_number') && $request['phone_number'] != $user->phone_number ? null : $user->phone_number_verified_at,
                'email' => RequestHas($request, 'email') ? $request['email'] : $user->email,
                'email_verified_at' => RequestHas($request, 'email') && $request['email'] != $user->email ? null : $user->email_verified_at,
            ]);
            $profile = $user->Profile;
            $profile->update([
                'first_name' => RequestHas($request, 'first_name') ? $request['first_name'] : $profile->first_name,
                'last_name' => RequestHas($request, 'last_name') ? $request['last_name'] : $profile->last_name,
                'gender' => RequestHas($request, 'gender') ? $request['gender'] : $profile->gender,
                'birthday' => RequestHas($request, 'birthday') ? $request['birthday'] : $profile->birthday,
                'address' => RequestHas($request, 'address') ? $request['address'] : $profile->address,
                'language' => RequestHas($request, 'language') ? $request['language'] : $profile->language,
                'calendar' => RequestHas($request, 'calendar') ? $request['calendar'] : $profile->calendar,
                'timezone' => RequestHas($request, 'timezone') ? $request['timezone'] : $profile->timezone,
            ]);
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
