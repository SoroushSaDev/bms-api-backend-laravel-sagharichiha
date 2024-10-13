<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::paginate(10);
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
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
                'name' => $request['name'],
            ]);
            if ($request->has('permissions'))
                $role->Permissions()->sync($request['permissions']);
            DB::commit();
            return redirect(route('roles.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }

    public function show(Role $role)
    {
        $role->Translate();
        return view('roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
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
            return redirect(route('roles.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }

    public function destroy(Role $role)
    {
        DB::beginTransaction();
        try {
            $role->Permissions()->sync([]);
            $role->delete();
            DB::commit();
            return redirect(route('roles.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }
}
