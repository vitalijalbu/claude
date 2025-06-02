<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Geo\IndexCities;
use App\Actions\Geo\IndexCountries;
use App\Actions\Geo\IndexNationalities;
use App\Actions\Geo\IndexProvinces;
use App\Actions\Geo\IndexRegions;
use App\Http\Resources\Api\CityResource;
use App\Http\Resources\Api\CountryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GeoController extends ApiController
{
    public function countries(Request $request, IndexCountries $action): JsonResponse
    {
        $data = $action->handle($request->all());

        return response()->json([
            'success' => true,
            'data' => CountryResource::collection($data),
        ]);
    }

    public function regions(Request $request, IndexRegions $action): JsonResponse
    {
        $data = $action->handle($request->all());

        return response()->json([
            'success' => true,
            'data' => CityResource::collection($data),
        ]);
    }

    public function provinces(Request $request, IndexProvinces $action): JsonResponse
    {
        $data = $action->handle($request->all());

        return response()->json([
            'success' => true,
            'data' => CityResource::collection($data),
        ]);
    }

    public function cities(Request $request, IndexCities $action): JsonResponse
    {
        $data = $action->handle($request->all());

        return response()->json([
            'success' => true,
            'data' => CityResource::collection($data),
        ]);
    }

    public function nationalities(Request $request, IndexNationalities $action): JsonResponse
    {
        $data = $action->handle();

        return response()->json([
            'success' => true,
            'data' => $data->map(fn($nationality) => [
                'id' => $nationality->id,
                'name' => $nationality->name,
                'country' => $nationality->country?->name,
            ]),
        ]);
    }
}