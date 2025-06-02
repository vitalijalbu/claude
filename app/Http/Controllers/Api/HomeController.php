<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Home\IndexHome;
use App\Http\Resources\Api\CategoryCollectionResource;
use App\Http\Resources\Api\CityResource;
use App\Http\Resources\Api\ListingCollectionResource;

class HomeController extends ApiController
{
    public function index(IndexHome $action)
    {
        $data = $action->handle();

        return response()->json([
            'success' => true,
            'data' => [
                'categories' => CategoryCollectionResource::collection($data['categories']),
                'regions' => $data['regions'],
                'listings' => ListingCollectionResource::collection($data['listings']),
                'cities' => CityResource::collection($data['cities']),
            ],
        ]);
    }
}