<?php

namespace App\Http\Controllers;

use App\Models\AugmentedReality;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AugmentedRealityController extends Controller
{
    public function index()
    {
        $ars = AugmentedReality::where('user_id', auth()->id())->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully fetched AR data',
            'data' => $ars,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable',
            'files' => 'required',
            'mindFile' => 'required',
            'files.*' => 'required|file',
        ]);
        DB::beginTransaction();
        try {
            $ar = AugmentedReality::create([
                'user_id' => auth()->id(),
                'name' => $request['name'],
                'description' => $request['description'],
            ]);
            if ($request->hasFile('mindFile')) {
                $file = $request->file('mindFile');
                $destinationPath = 'img/AR/' . $ar->id;
                $extension = 'txt';
                $fileName = rand(11111, 99999) . '.' . $extension;
                $fileSize = $file->getSize();
                $file->move($destinationPath, $fileName);
                File::create([
                    'user_id' => auth()->id(),
                    'fileable_type' => AugmentedReality::class,
                    'fileable_id' => $ar->id,
                    'path' => $destinationPath . '/' . $fileName,
                    'extension' => $extension,
                    'size' => $fileSize,
                ]);
            }
            $id = $ar->id;
            foreach($request['files'] as $i => $file) {
                $day = Carbon::today()->day;
                $year = Carbon::today()->year;
                $month = Carbon::today()->month;
                $destinationPath = "img/AR/$id/$year/$month/$day";
                $extension = $file->getClientOriginalExtension();
                $fileName = rand(11111, 99999) . '.' . $extension;
                $fileSize = $file->getSize();
                $file->move($destinationPath, $fileName);
                File::create([
                    'user_id' => auth()->id(),
                    'fileable_type' => AugmentedReality::class,
                    'fileable_id' => $id,
                    'path' => $destinationPath . '/' . $fileName,
                    'extension' => $extension,
                    'size' => $fileSize,
                    'use_type' => $i,
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully stored AR data',
                'data' => $ar,
            ], 200);
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error while storing AR data',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(AugmentedReality $ar)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully fetched AR data',
            'data' => $ar
        ], 200);
    }

    public function update(AugmentedReality $ar, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable',
        ]);
        DB::beginTransaction();
        try {
            $ar->update([
                'name' => $request['name'],
                'description' => $request['description'],
            ]);
            if ($request->hasFile('mindFile')) {
                $file = $request->file('mindFile');
                $destinationPath = 'img/AR/' . $ar->id;
                $extension = 'txt';
                $fileName = rand(11111, 99999) . '.' . $extension;
                $fileSize = $file->getSize();
                $file->move($destinationPath, $fileName);
                File::create([
                    'user_id' => auth()->id(),
                    'fileable_type' => AugmentedReality::class,
                    'fileable_id' => $ar->id,
                    'path' => $destinationPath . '/' . $fileName,
                    'extension' => $extension,
                    'size' => $fileSize,
                ]);
            }
            $id = $ar->id;
            foreach($request['files'] as $file) {
                $day = Carbon::today()->day;
                $year = Carbon::today()->year;
                $month = Carbon::today()->month;
                $destinationPath = "img/AR/$id/$year/$month/$day";
                $extension = $file->getClientOriginalExtension();
                $fileName = rand(11111, 99999) . '.' . $extension;
                $fileSize = $file->getSize();
                $file->move($destinationPath, $fileName);
                File::create([
                    'user_id' => auth()->id(),
                    'fileable_type' => AugmentedReality::class,
                    'fileable_id' => $id,
                    'path' => $destinationPath . '/' . $fileName,
                    'extension' => $extension,
                    'size' => $fileSize,
                    'use_type' => $request['index'],
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully updated AR data',
                'data' => $ar,
            ], 200);
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error while updating AR data',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(AugmentedReality $ar)
    {
        DB::beginTransaction();
        try {
            $ar->Files->each->delete();
            $ar->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully deleted AR',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error while deleting AR',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
