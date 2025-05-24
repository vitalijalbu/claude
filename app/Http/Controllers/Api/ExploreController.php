<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\ExploreResource;
use App\Services\Api\ExploreService;
use Illuminate\Http\Request;

class ExploreController extends ApiController
{
    protected ExploreService $exploreService;

    public function __construct(ExploreService $exploreService)
    {
        $this->exploreService = $exploreService;
    }

    public function index(Request $request)
    {
        $query = $request->get('query', null);

        $results = $this->exploreService->searchAll($query);

        return ExploreResource::collection($results);
    }
}
