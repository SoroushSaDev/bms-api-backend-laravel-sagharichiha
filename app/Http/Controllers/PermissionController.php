<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::paginate(10);
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);
        DB::beginTransaction();
        try {
            Permission::create([
                'name' => $request['name'],
            ]);
            DB::commit();
            return redirect(route('permissions.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }

    public function show(Permission $permission)
    {
        $permission->name = translate($permission->name);
        return view('permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
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
            return redirect(route('permissions.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }

    public function destroy(Permission $permission)
    {
        DB::beginTransaction();
        try {
            $permission->delete();
            DB::commit();
            return redirect(route('permissions.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }
}
