<?php

namespace App\Http\Controllers;

use App\Models\TagGroup;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

final class TagController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->query();
        $data = QueryBuilder::for(TagGroup::class)
            ->paginate($filters['per_page'] ?? 25)
            ->appends(request()->query());

        return Inertia::render('tags/index', [
            'page' => [
                'data' => $data,
                'filters' => $filters ?: null,
            ],
        ]);
    }

    public function show($id)
    {
        $data = TagGroup::where('id', $id)->with(['tags'])->firstOrFail();

        return Inertia::render('tags/show', ['page' => $data]);
    }
}
