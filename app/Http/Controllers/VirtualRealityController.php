<?php

namespace App\Http\Controllers;

use App\Models\VirtualReality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VirtualRealityController extends Controller
{
    public function index()
    {
        $vrs = VirtualReality::all();
        return response()->json([
            'status' => 'success',
            'data' => $vrs,
            'message' => 'VR data fetched successfully',
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'data' => 'required|json',
        ]);
        DB::beginTransaction();
        try {
            $vr = VirtualReality::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'title' => $request['title'],
                'data' => $request['data'],
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $vr,
                'message' => 'VR data stored successfully',
            ], 200);
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'data' => $e->getMessage(),
                'message' => 'Error while storing VR data'
            ], 500);
        }
    }

    public function show(VirtualReality $vr)
    {
        return response()->json([
            'status' => 'success',
            'data' => $vr,
            'message' => 'VR data fetched successfully',
        ], 200);
    }

    public function update(VirtualReality $vr, Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'data' => 'required|json',
        ]);
        DB::beginTransaction();
        try {
            $vr->update([
                // 'user_id' => auth()->check() ? auth()->id() : $vr->user_id,
                'title' => $request['title'],
                'data' => $request['data'],
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $vr,
                'message' => 'VR data updated successfully',
            ], 200);
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'data' => $e->getMessage(),
                'message' => 'Error while updating VR data'
            ], 500);
        }
    }

    public function destroy(VirtualReality $vr)
    {
        DB::beginTransaction();
        try {
            $vr->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'VR data deleted successfully',
            ], 200);
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'data' => $e->getMessage(),
                'message' => 'Error while deleting VR data'
            ], 500);
        }
    }
}
