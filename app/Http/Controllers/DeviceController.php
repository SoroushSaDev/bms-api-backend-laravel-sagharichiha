<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $devices = Device::when($request->has('type'), function ($query) use ($request) {
            $query->where('type', request('type'));
        })->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $devices,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'type' => 'nullable',
        ]);
        DB::beginTransaction();
        try {
            $device = new Device();
            $device->name = request('name');
            $device->type = request('type') ?? null;
            $device->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $device,
                'message' => __('device.created'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function show(Device $device): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $device,
        ], 200);
    }

    public function update(Request $request, Device $device): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'type' => 'nullable',
        ]);
        DB::beginTransaction();
        try {
            $device->name = request('name');
            $device->type = request('type') ?? null;
            $device->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $device,
                'message' => __('device.updated'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function destroy(Device $device): JsonResponse
    {
        DB::beginTransaction();
        try {
            $device->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => __('device.deleted'),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
