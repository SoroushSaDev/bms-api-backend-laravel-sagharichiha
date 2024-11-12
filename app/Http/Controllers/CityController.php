<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if (!City::CanShow())
            return response()->json([
                'status' => 'error',
                'message' => __('auth.403'),
            ], 403);
        $cities = City::with('Country')->select(['id', 'name', 'country_id'])->when($request->has('country_id'), function ($query) use ($request) {
            $query->where('country_id', $request->get('country_id'));
        })->paginate(10);
        $cities->map(function (City $city) {
            $city->Translate();
        });
        return response()->json([
            'status' => 'success',
            'data' => $cities,
        ], 200);
    }

    public function store(CityRequest $request): JsonResponse
    {
        if (!City::CanCreate())
            return response()->json([
               'status' => 'error',
               'message' => __('auth.403'),
            ], 403);
        DB::beginTransaction();
        try {
            $city = City::create([
               'country_id' => $request['country'],
               'name' => $request['name'],
            ]);
            DB::commit();
            $city->Translate();
            return response()->json([
                'status' => 'success',
                'data' => $city,
                'message' => __('city.created'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function show(City $city): JsonResponse
    {
        $city->Translate();
        return response()->json([
            'status' => 'success',
            'data' => $city,
        ], 200);
    }

    public function update(City $city, CityRequest $request): JsonResponse
    {
        if (!$city->CanEdit())
            return response()->json([
                'status' => 'error',
                'message' => __('auth.403'),
            ], 403);
        DB::beginTransaction();
        try {
            $city->update([
                'country_id' => $request['country'],
                'name' => $request['name'],
            ]);
            DB::commit();
            $city->Translate();
            return response()->json([
                'status' => 'success',
                'data' => $city,
                'message' => __('city.updated'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function destroy(City $city): JsonResponse
    {
        if (!City::CanDelete())
            return response()->json([
                'status' => 'error',
                'message' => __('auth.403'),
            ], 403);
        DB::beginTransaction();
        try {
            $city->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => __('city.deleted'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
