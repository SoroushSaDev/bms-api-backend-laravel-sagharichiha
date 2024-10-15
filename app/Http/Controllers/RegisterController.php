<?php

namespace App\Http\Controllers;

use App\Events\MqttMessageReceived;
use App\Http\Requests\RegisterRequest;
use App\Models\Device;
use App\Models\Register;
use App\Services\MqttService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\InvalidMessageException;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Exceptions\ProtocolViolationException;
use PhpMqtt\Client\Exceptions\RepositoryException;

class RegisterController extends Controller
{
    /**
     * @throws ConnectingToBrokerFailedException
     * @throws MqttClientException
     * @throws RepositoryException
     * @throws ConfigurationInvalidException
     * @throws ProtocolViolationException
     * @throws InvalidMessageException
     * @throws DataTransferException
     */
    public function index(Device $device, Request $request)
    {
        $mqttService = new MqttService();
        $mqttService->connect();
        $mqttService->subscribe($device->mqtt_topic, function ($topic, $message) {
            broadcast(new MqttMessageReceived($topic, $message));
        });
        $mqttService->loop(0);
        $registers = $device->Registers;
        $registers->map(function ($register) {
            $register->Translate();
        });
        return $request->ajax()
            ? view('registers.partial.table', compact('registers'))
            : view('registers.index', compact('device', 'registers'));
    }

    public function create(Device $device)
    {
        return view('registers.create', compact('device'));
    }

    public function store(Device $device, RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $register = new Register();
            $register->device_id = $device->id;
            $register->title = $request['title'];
            $register->unit = $request->has('unit') ? $request['unit'] : null;
            $register->type = $request->has('type') ? $request['type'] : null;
            $register->save();
            DB::commit();
            $register->Translate();
            return redirect(route('devices.registers', $device));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }

    public function show(Register $register)
    {
        $register->Translate();
        $device = $register->Device;
        return view('registers.show', compact('register', 'device'));
    }

    public function edit(Register $register)
    {
        $device = $register->Device;
        return view('registers.edit', compact('register', 'device'));
    }

    public function update(Register $register, RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $register->title = $request['title'];
            $register->unit = $request->has('unit') ? $request['unit'] : $register->unit;
            $register->type = $request->has('type') ? $request['type'] : $register->type;
            $register->save();
            DB::commit();
            $register->Translate();
            $device = $register->Device;
            return redirect(route('devices.registers', $device));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }

    public function destroy(Register $register)
    {
        DB::beginTransaction();
        try {
            $device = $register->Device;
            DB::commit();
            $register->delete();
            return redirect(route('devices.registers', $device));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }
}
