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
            $project->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $project,
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
            $project->name = $request->get('name');
            $project->type = $request->has('type') ? $request->get('type') : null;
            $project->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $project,
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
