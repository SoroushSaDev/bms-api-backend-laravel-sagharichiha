<?php

namespace App\Http\Controllers;

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
        $users = User::with('Profile')->when($request->has('parent_id'), function ($query) use ($request) {
            $query->where('parent_id', $request['parent_id']);
        })->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $users
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $parentId = $request->has('parent_id') ? $request['parent_id'] : (auth()->check() ? auth()->id() : 0);
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|max:255',
                'password' => 'required|confirmed|min:6|max:32',
                'phone_number' => [Rule::requiredIf(!$request->has('email')), 'unique:users,phone_number'],
                'email' => [Rule::requiredIf(!$request->has('phone_number')), 'email', 'unique:users,email'],
                'language' => ['nullable', Rule::in(array_keys(Translation::Languages))],
                'gender' => 'nullable|in:male,female',
                'first_name' => 'nullable|max:255',
                'last_name' => 'nullable|max:255',
                'birthday' => 'nullable|date',
                'address' => 'nullable',
            ]);
            $user = new User();
            $user->parent_id = $parentId;
            $user->name = $request['name'];
            $user->email = $request->has('email') ? $request['email'] : null;
            $user->password = Hash::make($request['password']);
            $user->phone_number = $request->has('phone_number') ? $request['phone_number'] : null;
            $user->save();
            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->first_name = $request->has('first_name') ? $request['first_name'] : null;
            $profile->last_name = $request->has('last_name') ? $request['last_name'] : null;
            $profile->gender = $request->has('gender') ? $request['gender'] : null;
            $profile->birthday = $request->has('birthday') ? $request['birthday'] : null;
            $profile->address = $request->has('address') ? $request['address'] : null;
            $profile->language = $request->has('language') ? $request['language'] : null;
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
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function show(User $user): JsonResponse
    {
        $user->Profile->Translate();
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ], 200);
    }

    public function update(User $user, Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
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
            ]);
            $parentId = $request->has('parent_id') ? $request['parent_id'] : $user->parent_id;
            $user->name = $request['name'];
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
                'message' => $exception->getMessage()
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
                'message' => $exception->getMessage()
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
}
