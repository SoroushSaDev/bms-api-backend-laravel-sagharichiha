<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComponentRequest;
use App\Models\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComponentController extends Controller
{
    public function index(Request $request)
    {
        $forms = Component::with('Category')->when(auth()->user()->type != 'admin', function ($query) {
            $query->where('user_id', auth()->id());
        })->when($request->has('category'), function ($query) use ($request) {
            $query->where('category_id', $request['category']);
        })->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully fetched components',
            'data' => $forms,
        ], 200);
    }

    public function store(ComponentRequest $request)
    {
        DB::beginTransaction();
        try {
            $form = Component::create([
                'user_id' => auth()->id(),
                'name' => $request['name'],
                'content' => $request['content'],
                'category_id' => $request->has('category') ? $request['category'] : null,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully created component',
                'data' => $form,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error while creating component',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Component $component)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully fetched component',
            'data' => $component
        ], 200);
    }

    public function update(Component $component, ComponentRequest $request)
    {
        DB::beginTransaction();
        try {
            $component->update([
                'name' => $request['name'],
                'content' => $request['content'],
                'category_id' => $request->has('category') ? $request['category'] : $component->category_id,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully updated component',
                'data' => $component,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error while updating form',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Component $component)
    {
        DB::beginTransaction();
        try {
            $component->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully deleted component',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error while deleting component',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
