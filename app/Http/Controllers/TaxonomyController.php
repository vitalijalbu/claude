<?php

namespace App\Http\Controllers;

use App\Models\TaxonomyGroup;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

final class TaxonomyController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->query();
        $data = QueryBuilder::for(TaxonomyGroup::class)
            ->paginate($filters['per_page'] ?? 25)
            ->appends(request()->query());

        return Inertia::render('taxonomies/index', [
            'page' => [
                'data' => $data,
                'filters' => $filters ?: null,
            ],
        ]);
    }

    public function show($id)
    {
        $data = TaxonomyGroup::where('id', $id)->with(['taxonomies'])->firstOrFail();

        return Inertia::render('taxonomies/show', ['page' => $data]);
    }
}
