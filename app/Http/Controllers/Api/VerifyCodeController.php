<?php

namespace App\Http\Controllers\Api;

use App\Mail\VerifyEmail;
use App\Models\VerifyCode;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerifyCodeController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:email,phone_number',
        ]);
        $now = Carbon::now();
        $type = $request['type'];
        $expiration = $type == 'email' ? 30 : 5;
        DB::beginTransaction();
        try {
            $user = auth()->user();
            if (!is_null($user->{$type . '_verified_at'}))
                return response()->json([
                    'status' => 'error',
                    'message' => __('verification.' . $type . '.verified'),
                ], 403);
            $userId = $user->id;
            VerifyCode::InvokeCodes($userId, $type);
            $code = new VerifyCode();
            $code->user_id = $userId;
            $code->type = $type;
            $code->code = rand(100000, 999999);
            $code->expiration = $now->addMinutes($expiration);
            $code->save();
            DB::commit();
            if ($type == 'email')
                Mail::to($user->email)->send(new VerifyEmail($code->code));
            return response()->json([
                'status' => 'success',
                'message' => __('verification.' . $type . '.sent'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:email,phone_number',
            'code' => 'required|exists:verify_codes,code',
        ]);
        $type = $request['type'];
        $user = auth()->user();
        $now = Carbon::now();
        $userId = $user->id;
        $code = VerifyCode::where('user_id', $userId)->where('type', $type)->where('code', $request['code'])->get()->last();
        if (is_null($code)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Verification code is incorrect.',
            ], 403);
        }
        if (!is_null($code->invoked_at))
            return response()->json([
                'status' => 'error',
                'message' => __('verification.invoked'),
            ], 403);
        if (!is_null($code->used_at))
            return response()->json([
                'status' => 'error',
                'message' => __('verification.used'),
            ], 403);
        if ($code->expiration < $now)
            return response()->json([
                'status' => 'error',
                'message' => __('verification.expired'),
            ], 403);
        DB::beginTransaction();
        try {
            $code->used_at = Carbon::now();
            $code->save();
            $user->{$type . '_verified_at'} = $now;
            $user->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => __('verification.' . $type . '.verified'),
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
