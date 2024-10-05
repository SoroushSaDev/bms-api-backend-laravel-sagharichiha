<?php

namespace App\Http\Controllers;

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
        })->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $registers,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'device_id' => 'required',
            'title' => 'string|required',
            'unit' => 'string|nullable',
        ]);
        DB::beginTransaction();
        try {
            $register = new Register();
            $register->device_id = $request['device_id'];
            $register->title = $request['title'];
            $register->unit = $request->has('unit') ? $request['unit'] : null;
            $register->save();
            DB::commit();
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
        return response()->json([
            'status' => 'success',
            'data' => $register,
        ], 200);
    }

    public function update(Register $register, Request $request): JsonResponse
    {
        $request->validate([
            'device_id' => 'required',
            'title' => 'string|required',
            'unit' => 'string|nullable',
        ]);
        DB::beginTransaction();
        try {
            $register->device_id = $request['device_id'];
            $register->title = $request['title'];
            $register->unit = $request->has('unit') ? $request['unit'] : null;
            DB::commit();
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
