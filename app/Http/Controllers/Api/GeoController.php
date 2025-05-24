<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\CityResource;
use App\Http\Resources\Api\CountryResource;
use App\Services\Api\GeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GeoController extends ApiController
{
    protected GeoService $geoService;

    public function __construct(GeoService $geoService)
    {
        $this->geoService = $geoService;
    }

    public function countries(Request $request)
    {
        $params = $request->all();
        $data = $this->geoService->findAllCountries($params);

        return CountryResource::collection($data);
    }

    public function regions(Request $request)
    {
        $params = $request->all();
        $data = $this->geoService->findAllRegions($params);

        return CityResource::collection($data);
    }

    public function provinces(Request $request)
    {
        $params = $request->all();
        $data = $this->geoService->findAllProvinces($params);

        return CityResource::collection($data);
    }

    public function cities(Request $request)
    {
        $params = $request->all();
        $data = $this->geoService->findAllCities($params);

        return CityResource::collection($data);
    }

    public function nationalities(Request $request): JsonResponse
    {
        $params = $request->all();
        $data = $this->geoService->findAllNationalities($params);

        return response()->json([
            'data' => $data,
        ]);
    }
}
