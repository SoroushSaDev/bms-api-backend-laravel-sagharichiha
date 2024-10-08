<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $permissions,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);
        DB::beginTransaction();
        try {
            $permission = Permission::create([
                'name' => $request['name'],
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $permission,
                'message' => __('permission.created'),
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function show(Permission $permission)
    {
        $permission->name = translate($permission->name);
        return response()->json([
            'status' => 'success',
            'data' => $permission,
        ], 200);
    }

    public function update(Permission $permission, Request $request)
    {
        $request->validate([
            'name' => ['required', ($request['name'] != $permission['name'] ? 'unique:permissions,name' : '')],
        ]);
        DB::beginTransaction();
        try {
            $permission->update([
                'name' => $request['name'],
            ]);
            DB::commit();
            $permission->name = translate($permission->name);
            return response()->json([
                'status' => 'success',
                'data' => $permission,
                'message' => __('permission.updated'),
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function destroy(Permission $permission)
    {
        DB::beginTransaction();
        try {
            $permission->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => __('permission.deleted'),
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
