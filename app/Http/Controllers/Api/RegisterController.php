<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Register;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $registers = Register::when($request->has('device_id'), function ($query) use ($request) {
            $query->where('device_id', $request['device_id']);
        })->when(auth()->user()->type != 'admin', function ($query) {
            $query->where('user_id', auth()->id());
        })->paginate(10);
        $registers->map(function ($register) {
            $register->Translate();
        });
        return response()->json([
            'status' => 'success',
            'data' => $registers,
        ], 200);
    }

    public function store(RegisterRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $register = new Register();
            $register->device_id = $request['device_id'];
            $register->title = $request['title'];
            $register->unit = $request->has('unit') ? $request['unit'] : null;
            $register->type = $request->has('type') ? $request['type'] : null;
            $register->save();
            DB::commit();
            $register->Translate();
            return response()->json([
                'status' => 'success',
                'data' => $register,
                'message' => __('register.created')
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function show(Register $register): JsonResponse
    {
        $register->Translate();
        return response()->json([
            'status' => 'success',
            'data' => $register,
        ], 200);
    }

    public function update(Register $register, RegisterRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $register->device_id = $request['device_id'];
            $register->title = $request['title'];
            $register->unit = $request->has('unit') ? $request['unit'] : $register->unit;
            $register->type = $request->has('type') ? $request['type'] : $register->type;
            $register->save();
            DB::commit();
            $register->Translate();
            return response()->json([
                'status' => 'success',
                'data' => $register,
                'message' => __('register.updated'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function destroy(Register $register): JsonResponse
    {
        DB::beginTransaction();
        try {
            $register->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => __('register.deleted'),
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
