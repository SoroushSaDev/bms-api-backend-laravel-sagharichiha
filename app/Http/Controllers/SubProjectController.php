<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\SubProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SubProjectController extends Controller
{
    public function index(Project $project)
    {
        $subs = $project->SubProjects;
        return response()->json([
            'status' => 'success',
            'data' => $subs,
            'message' => 'SubProjects fetched successfully',
        ], 200);
    }

    public function store(Project $project, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'files.*' => 'required|exists:files,id',
            'forms.*' => 'required|exists:forms,id',
        ]);
        DB::beginTransaction();
        try {
            $targets = [];
            if ($project->type == 'ImageProcessing') {
                foreach ($request['files'] as $i => $file) {
                    foreach ($request['forms'][$i] as $j => $form) {
                        $targets[$file][$j] = $form;
                    }
                }
            }
            $sub = SubProject::create([
                'project_id' => $project->id,
                'name' => $request['name'],
                'description' => $request['description'],
                'targets' => json_encode($targets),
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $sub,
                'message' => 'SubProject stored successfully',
            ], 200);
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'data' => $e->getMessage(),
                'message' => 'Error while storing SubProject',
            ], 500);
        }
    }
}
