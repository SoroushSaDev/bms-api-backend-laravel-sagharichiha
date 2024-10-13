<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function index(Request $request)
    {
        if (!City::CanShow())
            abort(403);
        $cities = City::with('Country')->when($request->has('country_id'), function ($query) use ($request) {
            $query->where('country_id', $request->get('country_id'));
        })->paginate(10);
        $cities->map(function (City $city) {
            $city->Translate();
        });
        return view('cities.index', compact('cities'));
    }

    public function create()
    {
        $countries = Country::all();
        $countries->map(function (Country $country) {
            $country->name = translate($country->en_name);
        });
        return view('cities.create', compact('countries'));
    }

    public function store(CityRequest $request)
    {
        if (!City::CanCreate())
            abort(403);
        DB::beginTransaction();
        try {
            $city = City::create([
                'country_id' => $request['country'],
                'name' => $request['name'],
            ]);
            DB::commit();
            $city->Translate();
            return redirect(route('cities.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }

    public function show(City $city)
    {
        $city->Translate();
        return view('cities.show', compact('city'));
    }

    public function edit(City $city)
    {
        $countries = Country::all();
        $countries->map(function (Country $country) {
            $country->name = translate($country->en_name);
        });
        return view('cities.edit', compact('city', 'countries'));
    }

    public function update(City $city, CityRequest $request)
    {
        if (!$city->CanEdit())
            abort(403);
        DB::beginTransaction();
        try {
            $city->update([
                'country_id' => $request['country'],
                'name' => $request['name'],
            ]);
            DB::commit();
            $city->Translate();
            return redirect(route('cities.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }

    public function destroy(City $city)
    {
        if (!$city->CanDelete())
            abort(403);
        DB::beginTransaction();
        try {
            $city->delete();
            DB::commit();
            return redirect(route('cities.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }
}
