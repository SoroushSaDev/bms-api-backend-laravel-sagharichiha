<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceRequest;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = Device::with(['User', 'Registers'])->when(auth()->user()->type != 'admin', function ($query) {
            $query->where('user_id', auth()->id());
        })->when(auth()->user()->type == 'admin', function ($query) {
            $query->where('parent_id', 0);
        })->when($request->has('type'), function ($query) use ($request) {
            $query->where('type', $request['type']);
        })->paginate(10);
        $devices->map(function ($device) {
            $device->Translate();
        });
        return view('devices.index', compact('devices'));
    }

    public function create()
    {
        return view('devices.create');
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
            $device->mqtt_topic = $request->has('topic') ? $request['topic'] : 'METARIOM/' . str_replace(' ', '_', $device->name);
            $device->save();
            $device->SendToClient();
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

    public function edit(Device $device)
    {
        return view('devices.edit', compact('device'));
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
            $device->mqtt_topic = $request->has('topic') ? $request['topic'] : 'METARIOM/' . str_replace(' ', '_', $device->name);
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
