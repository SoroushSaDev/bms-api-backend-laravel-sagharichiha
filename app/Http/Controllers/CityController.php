<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
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
        DB::beginTransaction();
        try {
            $city = new City();
            $city->country_id = $request['country'];
            $city->name = $request['name'];
            $city->save();
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
        DB::beginTransaction();
        try {
            $city->country_id = $request['country'];
            $city->name = $request['name'];
            $city->save();
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
