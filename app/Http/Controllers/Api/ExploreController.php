<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Search\SearchAll;
use App\Http\Resources\Api\ExploreResource;
use Illuminate\Http\Request;

class ExploreController extends ApiController
{
    public function index(Request $request, SearchAll $action)
    {
        $query = $request->get('query', null);
        $results = $action->handle($query);

        return response()->json([
            'success' => true,
            'data' => ExploreResource::collection($results),
        ]);
    }
}