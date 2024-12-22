<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Device;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $projects = Project::with(['User', 'City', 'Devices'])->when(auth()->user()->type != 'admin', function ($query) {
                $query->where('user_id', auth()->id());
            })->when($request->has('city'), function ($query) use ($request) {
                $query->where('city_id', $request['city']);
            })->get();
        return response()->json([
            'status' => 'success',
            'data' => $projects,
            'message' => 'Projects fetched successfully',
        ], 200);
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $project = new Project();
            $project->name = $request['name'];
            $project->type = $request['type'];
            $project->city_id = $request['city'];
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
            return response()->json([
                'status' => 'success',
                'data' => $project,
                'message' => __('project.created'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function show(Project $project): JsonResponse
    {
        $project->Translate();
        return response()->json([
            'status' => 'success',
            'data' => $project,
        ], 200);
    }

    public function update(Project $project, ProjectRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $project->name = $request['name'];
            $project->type = $request['type'];
            $project->city_id = $request['city'];
            $project->address = $request->has('address') ? $request['address'] : $project->address;
            $project->description = $request->has('description') ? $request['description'] : $project->description;
            $project->save();
            if ($request->has('devices')) {
                $devices = $project->Devices->pluck('id')->toArray();
                foreach ($request['devices'] as $deviceId) {
                    if (!in_array($deviceId, $devices)) {
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
            return response()->json([
                'status' => 'success',
                'data' => $project,
                'message' => __('project.updated'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function destroy(Project $project): JsonResponse
    {
        DB::beginTransaction();
        try {
            $project->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => __('project.deleted'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function types()
    {
        return response()->json([
            'status' => 'success',
            'data' => Project::Types,
            'message' => 'Project types fetched successfully',
        ], 200);
    }
}
