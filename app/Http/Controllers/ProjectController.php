<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $projects,
        ], 200);
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $project = new Project();
            $project->name = $request['name'];
            $project->type = $request->has('type') ? $request['type'] : null;
            $project->brand = $request->has('brand') ? $request['brand'] : null;
            $project->model = $request->has('model') ? $request['model'] : null;
            $project->description = $request->has('description') ? $request['description'] : null;
            $project->lan = $request->has('lan') ? $request['lan'] : null;
            $project->wifi = $request->has('wifi') ? $request['wifi'] : null;
            $project->save();
            DB::commit();
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
            $project->type = $request->has('type') ? $request['type'] : $project->type;
            $project->brand = $request->has('brand') ? $request['brand'] : $project->brand;
            $project->model = $request->has('model') ? $request['model'] : $project->model;
            $project->description = $request->has('description') ? $request['description'] : $project->description;
            $project->lan = $request->has('lan') ? $request['lan'] : $project->lan;
            $project->wifi = $request->has('type') ? $request['wifi'] : $project->wifi;
            $project->save();
            DB::commit();
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
}
