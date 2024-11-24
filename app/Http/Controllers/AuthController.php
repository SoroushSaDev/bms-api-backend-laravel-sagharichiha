<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);    
            $profile = Profile::create([
                'user_id' => $user->id,
                'language' => $request->has('lang') ? $request['lang'] : 'en',
            ]);
            event(new Registered($user));
            $token = $user->createToken('auth_token')->plainTextToken;    
            $lang = $user->Profile->language;
            App::setLocale($lang);
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'data' => [
                    'user' => $user,
                    'profile' => $profile,
                ],
            ], 200);
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating user',
                'error' => $e,
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
        if (!Auth::attempt($request->only('email', 'password')))
            return response()->json(['message' => 'Invalid login credentials'], 401);
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        $lang = $user->Profile->language;
        App::setLocale($lang);
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Logged in successfully',
            'data' => [
                'user' => $user,
                'profile' => $user->Profile,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => __('auth.logout'),
        ], 200);
    }
}
