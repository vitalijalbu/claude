<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

final class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->query();
        $data = QueryBuilder::for(Category::class)
            ->allowedFilters(['name', 'email'])
            ->allowedSorts(['name', 'total_listings'])
            ->paginate($filters['per_page'] ?? 25)
            ->appends(request()->query());

        return Inertia::render('categories/index', [
            'page' => [
                'data' => $data,
                'filters' => $filters ?: null,
            ],
        ]);
    }

    public function show($id)
    {
        $data = Category::where('id', $id)->firstOrFail();

        return Inertia::render('categories/show', ['data' => $data]);
    }
}
