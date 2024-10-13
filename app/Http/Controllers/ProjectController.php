<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\City;
use App\Models\Device;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::paginate(10);
        $projects->map(function (Project $project) {
            $project->Translate();
        });
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $users = User::all();
        $cities = City::all();
        $devices = Device::where('parent_id', 0)->get();
        return view('projects.create', compact('users', 'cities', 'devices'));
    }

    public function store(ProjectRequest $request)
    {
        DB::beginTransaction();
        try {
            $project = new Project();
            $project->name = $request['name'];
            $project->city_id = $request['city'];
            $project->user_id = $request->has('user') ? $request['user'] : null;
            $project->address = $request->has('address') ? $request['address'] : null;
            $project->description = $request->has('description') ? $request['description'] : null;
            $project->save();
            if ($request->has('devices')) {
                foreach ($request['devices'] as $deviceId) {
                    $device = Device::find($deviceId);
                    $newDevice = $device->replicate();
                    $newDevice->project_id = $project->id;
                    $newDevice->parent_id = $device->id;
                    $newDevice->save();
                    foreach ($device->Registers as $register) {
                        $newRegister = $register->replicate();
                        $newRegister->parent_id = $register->id;
                        $newRegister->device_id = $newDevice->id;
                        $newRegister->save();
                    }
                }
            }
            DB::commit();
            $project->Translate();
            return redirect(route('projects.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }

    public function show(Project $project)
    {
        $project->Translate();
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $users = User::all();
        $cities = City::all();
        $devices = Device::where('parent_id', 0)->get();
        return view('projects.edit', compact('project', 'users', 'cities', 'devices'));
    }

    public function update(Project $project, ProjectRequest $request)
    {
        DB::beginTransaction();
        try {
            $project->name = $request['name'];
            $project->city_id = $request['city'];
            $project->user_id = $request->has('user') ? $request['user'] : $project->user_id;
            $project->address = $request->has('address') ? $request['address'] : $project->address;
            $project->description = $request->has('description') ? $request['description'] : $project->description;
            $project->save();
            foreach ($project->Devices as $device) {
                if (!in_array($device->parent_id, $request['devices']))
                    $device->delete();
            }
            if ($request->has('devices')) {
                foreach ($request['devices'] as $deviceId) {
                    if (!$project->HasDevice($deviceId)) {
                        $device = Device::find($deviceId);
                        $newDevice = $device->replicate();
                        $newDevice->project_id = $project->id;
                        $newDevice->parent_id = $device->id;
                        $newDevice->save();
                        foreach ($device->Registers as $register) {
                            $newRegister = $register->replicate();
                            $newRegister->parent_id = $register->id;
                            $newRegister->device_id = $newDevice->id;
                            $newRegister->save();
                        }
                    }
                }
            }
            DB::commit();
            $project->Translate();
            return redirect(route('projects.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }

    public function destroy(Project $project)
    {
        DB::beginTransaction();
        try {
            $devices = $project->Devices;
            foreach ($devices as $device) {
                foreach ($device->Registers as $register) {
                    $register->delete();
                }
                $device->delete();
            }
            $project->delete();
            DB::commit();
            return redirect(route('projects.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }
}
