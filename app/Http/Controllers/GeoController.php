<?php

namespace App\Http\Controllers;

use App\Models\Geo\Country;
use App\Models\Geo\Region;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

final class GeoController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->query();
        $data = QueryBuilder::for(Country::withCount('regions'))
            ->paginate($filters['per_page'] ?? 25)
            ->appends(request()->query());

        return Inertia::render('geo/index', [
            'page' => [
                'data' => $data,
                'filters' => $filters ?: null,
            ],
        ]);
    }

    public function show(Request $request)
    {
        $filters = $request->query();
        $data = QueryBuilder::for(Region::with('country'))
            ->paginate($filters['per_page'] ?? 25)
            ->appends(request()->query());

        return Inertia::render('geo/regions', [
            'page' => [
                'data' => $data,
                'filters' => $filters ?: null,
            ],
        ]);
    }

    public function cities(Request $request)
    {
        $filters = $request->query();
        $data = QueryBuilder::for(Region::class)
            ->paginate($filters['per_page'] ?? 25)
            ->appends(request()->query());

        return Inertia::render('geo/regions', [
            'page' => [
                'data' => $data,
                'filters' => $filters ?: null,
            ],
        ]);
    }
}
