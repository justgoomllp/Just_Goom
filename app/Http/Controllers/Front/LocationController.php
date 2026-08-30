<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    public function countries(): JsonResponse
    {
        $countries = Country::orderBy('name')->get(['id', 'name', 'code']);

        return response()->json($countries);
    }

    public function states(Country $country): JsonResponse
    {
        $states = $country->states()->orderBy('name')->get(['id', 'name']);

        return response()->json($states);
    }

    public function cities(State $state): JsonResponse
    {
        $cities = $state->cities()->orderBy('name')->get(['id', 'name']);

        return response()->json($cities);
    }
}
