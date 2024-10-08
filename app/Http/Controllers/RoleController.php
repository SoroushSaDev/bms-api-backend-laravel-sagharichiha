<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $roles,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);
        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request['name'],
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $role,
                'message' => __('role.created'),
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function update(Role $role, Request $request)
    {
        $request->validate([
            'name' => ['required', ($request['name'] != $role['name'] ? 'unique:permissions,name' : '')],
        ]);
        DB::beginTransaction();
        try {
            $role->update([
                'name' => $request['name'],
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $role,
                'message' => __('role.updated'),
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function destroy(Role $role)
    {
        DB::beginTransaction();
        try {
            $role->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => __('role.deleted'),
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
