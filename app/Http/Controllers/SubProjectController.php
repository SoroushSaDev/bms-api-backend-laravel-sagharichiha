<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\Project;
use App\Models\SubProject;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SubProjectController extends Controller
{
    public function index(Project $project)
    {
        $subs = $project->SubProjects;
        $subs->map(function($sub) use($project) {
            $token = AccessToken::where('user_id', auth()->id())->where('tokenable_type', SubProject::class)->where('tokenable_id', $sub->id)
                    ->whereNull('expired_at')->first();
            if(!$token) {
                $token = AccessToken::create([
                    'user_id' => auth()->id(),
                    'tokenable_type' => SubProject::class,
                    'tokenable_id' => $sub->id,
                ]);
            }
            $sub->token = $token->id . '|' . Hash::make('SubAR' . $sub->id . 'Project' . $project->id . 'User' . auth()->id());
        });
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
            'forms.*' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $targets = [];
            if ($project->type == 'ImageProcessing') {
                foreach ($request['files'] as $i => $file) {
                    foreach ($request['forms'][$file] as $j => $form) {
                        $targets[$file][$j] = $form;
                    }
                }
            }
            $sub = SubProject::create([
                'user_id' => auth()->id(),
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

    public function show(Project $project, SubProject $sub)
    {
        return response()->json([
            'status' => 'success',
            'data' => $sub,
            'message' => 'SubProject fetched successfully',
        ], 200);
    }

    public function update(Project $project, SubProject $sub, Request $request)
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
            $sub->update([
                'name' => $request['name'],
                'description' => $request['description'],
                'targets' => json_encode($targets),
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $sub,
                'message' => 'SubProject updated successfully',
            ], 200);
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'data' => $e->getMessage(),
                'message' => 'Error while updating SubProject',
            ], 500);
        }
    }

    public function destroy(Project $project, SubProject $sub)
    {
        DB::beginTransaction();
        try {
            $sub->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'SubProject deleted successfully',
            ], 200);
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'data' => $e->getMessage(),
                'message' => 'Error while deleting SubProject',
            ], 500);
        }
    }
}
