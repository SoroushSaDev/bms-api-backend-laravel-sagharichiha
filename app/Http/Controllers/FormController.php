<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::all();
        return response()->json([
            'status' => 'success',
            'data' => $forms,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:forms,name',
            'content' => 'required|string',
            'objects' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $form = Form::create([
                'user_id' => auth()->id(),
                'name' => $request['name'],
                'content' => $request['content'],
                'objects' => $request['objects'],
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Form created successfully',
                'data' => $form,
            ], 200);
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error while creating form',
                'data' => $e,
            ], 500);
        }
    }

    public function show(Form $form)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully fetched form data',
            'data' => $form
        ], 200);
    }

    public function update(Form $form, Request $request)
    {
        $request->validate([
            'name' => 'required|string' . ($request['name'] != $form->name ? '|unique:forms,name' : ''),
            'content' => 'required|string',
            'objects' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $form->update([
                'name' => $request['name'],
                'content' => $request['content'],
                'objects' => $request['objects'],
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Form updated successfully',
                'data' => $form,
            ], 200);
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error while creating form',
                'data' => $e,
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
                'message' => 'Error while creating form',
                'errors' => $e,
            ], 500);
        }
    }
}
