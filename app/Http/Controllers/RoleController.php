<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::with(['Permissions', 'Users'])->when(auth()->user()->type != 'admin', function($query) {
            $query->where('user_id', auth()->id());
        })->get();
        return response()->json([
            'status' => 'success',
            'data' => $roles,
        ], 200);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|unique:permissions,name',
                'permissions.*' => 'nullable|exists:permissions,id',
            ]);
            $role = Role::create([
                'user_id' => auth()->id(),
                'name' => $request['name'],
            ]);
            if ($request->has('permissions'))
                $role->Permissions()->sync($request['permissions']);
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

    public function show(Role $role)
    {
        $role->Translate();
        return response()->json([
            'status' => 'success',
            'data' => $role,
        ], 200);
    }

    public function update(Role $role, Request $request)
    {
        $request->validate([
            'name' => ['required', ($request['name'] != $role['name'] ? 'unique:permissions,name' : '')],
            'permissions.*' => 'nullable|exists:permissions,id',
        ]);
        DB::beginTransaction();
        try {
            $role->update([
                'name' => $request['name'],
            ]);
            $role->Permissions()->sync($request['permissions']);
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
            $role->Permissions()->sync([]);
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
