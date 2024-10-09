<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceRequest;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = Device::with(['User', 'Registers'])->select(['id', 'user_id', 'name', 'type', 'brand', 'model', 'description'])
            ->when(auth()->user()->type != 'admin', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($request->has('type'), function ($query) use ($request) {
                $query->where('type', $request['type']);
            })->get();
        $devices->map(function ($device) {
            $device->Translate();
        });
        return view('devices.index', compact('devices'));
    }

    public function store(DeviceRequest $request)
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
            return redirect(route('devices.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }

    public function show(Device $device)
    {
        $device->Translate();
        return view('devices.show', compact('device'));
    }

    public function update(DeviceRequest $request, Device $device)
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
            return redirect(route('devices.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }

    public function destroy(Device $device)
    {
        DB::beginTransaction();
        try {
            $device->delete();
            DB::commit();
            return redirect(route('devices.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }
}
