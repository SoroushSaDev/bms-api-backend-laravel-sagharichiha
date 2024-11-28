<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', auth()->id())->orWhereNull('user_id')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Categories fetched successfully',
            'data' => $categories,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(Category::Types)],
        ]);
        DB::beginTransaction();
        try {
            $category = Category::create([
                'user_id' => auth()->id(),
                'type' => $request['type'],
                'title' => $request['title'],
                'description' => $request['description'],
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully',
                'data' => $category,
            ], 200);
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error while creating category',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Category $category)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Category fetched successfully',
            'data' => $category,
        ], 200);
    }

    public function update(Category $category, Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(Category::Types)],
        ]);
        DB::beginTransaction();
        try {
            $category->update([
                'type' => $request['type'],
                'title' => $request['title'],
                'description' => $request['description'],
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully',
                'data' => $category,
            ], 200);
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error while updating category',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            $category->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully deleted category',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error while deleting category',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
