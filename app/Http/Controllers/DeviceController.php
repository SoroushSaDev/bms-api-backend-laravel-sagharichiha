<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceRequest;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Connection;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = Device::with(['User', 'Registers'])->select(['id', 'user_id', 'name', 'brand', 'model', 'description'])
            ->when(auth()->user()->type != 'admin', function ($query) {
                $query->where('user_id', auth()->id());
            })->paginate(10);
        $devices->map(function ($device) {
            $device->Translate();
        });
        return response()->json([
            'status' => 'success',
            'data' => $devices,
        ], 200);
    }

    public function store(DeviceRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $device = new Device();
            $device->name = $request['name'];
            $device->type = $request->has('type') ? $request['type'] : null;
            $device->brand = $request->has('brand') ? $request['brand'] : null;
            $device->model = $request->has('model') ? $request['model'] : null;
            $device->description = $request->has('description') ? $request['description'] : null;
            $device->lan = $request->has('lan') ? $request['lan'] : null;
            $device->wifi = $request->has('wifi') ? $request['wifi'] : null;
            $device->save();
            DB::commit();
            $device->Translate();
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
        $device->Translate();
        return response()->json([
            'status' => 'success',
            'data' => $device,
        ], 200);
    }

    public function update(DeviceRequest $request, Device $device): JsonResponse
    {
        DB::beginTransaction();
        try {
            $device->name = $request['name'];
            $device->type = $request->has('type') ? $request['type'] : $device->type;
            $device->brand = $request->has('brand') ? $request['brand'] : $device->brand;
            $device->model = $request->has('model') ? $request['model'] : $device->model;
            $device->description = $request->has('description') ? $request['description'] : $device->description;
            $device->lan = $request->has('lan') ? $request['lan'] : $device->lan;
            $device->wifi = $request->has('type') ? $request['wifi'] : $device->wifi;
            $device->save();
            DB::commit();
            $device->Translate();
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

    public function destroy(Device $device)
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
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function GetConnections()
    {
        $connections = Connection::select(['id', 'name'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $connections,
        ], 200);
    }
}
