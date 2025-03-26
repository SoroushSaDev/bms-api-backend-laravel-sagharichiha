<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormRequest;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function index(Request $request)
    {
        $forms = Form::with('Category')->when(auth()->user()->type != 'admin', function ($query) {
            $query->where('user_id', auth()->id());
        })->when($request->has('category'), function ($query) use ($request) {
            $query->where('category_id', $request['category']);
        })->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully fetched forms',
            'data' => $forms,
        ], 200);
    }

    public function store(FormRequest $request)
    {
        DB::beginTransaction();
        try {
            $form = Form::create([
                'user_id' => auth()->id(),
                'name' => $request['name'],
                'content' => $request->has('content') ? $request['content'] : null,
                'objects' => $request->has('objects') ? $request['objects'] : null,
                'category_id' => $request->has('category') ? $request['category'] : 0,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully created form',
                'data' => $form,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error while creating form',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Form $form)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully fetched form',
            'data' => $form
        ], 200);
    }

    public function update(Form $form, FormRequest $request)
    {
        DB::beginTransaction();
        try {
            $form->update([
                'name' => $request->has('name') ? $request['name'] : $form->name,
                'content' => $request['content'],
                'objects' => $request->has('objects') ? $request['objects'] : $form->objects,
                'category_id' => $request->has('category') ? $request['category'] : $form->category_id,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully updated form',
                'data' => $form,
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

    public function destroy(Form $form)
    {
        DB::beginTransaction();
        try {
            $form->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully deleted form',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error while deleting form',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
