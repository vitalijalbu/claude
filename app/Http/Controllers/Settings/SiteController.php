<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

final class SiteController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->query();
        $data = QueryBuilder::for(Site::class)
            ->paginate($filters['per_page'] ?? 25)
            ->appends(request()->query());

        return Inertia::render('settings/sites', [
            'page' => [
                'data' => $data,
                'filters' => $filters ?: null,
            ],
        ]);
    }

    public function show($id)
    {
        $data = Site::where('id', $id)->firstOrFail();

        return Inertia::render('settings/sites/show', ['data' => $data]);
    }

    public function create()
    {
        return Inertia::render('settings/sites/create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
        ]);

        Site::create($data);

        return redirect()->route('settings/sites.index')->with('success', 'Site created successfully.');
    }

    public function edit($id)
    {
        $data = Site::where('id', $id)->firstOrFail();

        return Inertia::render('settings/sites/edit', ['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
        ]);

        return redirect()->route('settings/sites/index')->with('success', 'Site updated successfully.');
    }

    public function destroy($id)
    {

        return redirect()->route('settings/sites.index')->with('success', 'Site deleted successfully.');
    }

    /*
    * API
    */
    public function indexAPI(Request $request)
    {
        $filters = $request->query();
        $data = QueryBuilder::for(Site::class)
            ->paginate($filters['per_page'] ?? 25)
            ->appends(request()->query());

        return response()->json($data);
    }
}
