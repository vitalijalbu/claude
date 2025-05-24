<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->query();
        $data = QueryBuilder::for(Profile::class)
            ->with(['user', 'listings'])
            ->allowedFilters(['name', 'email'])
            ->allowedSorts(['name', 'total_listings'])
            ->paginate($filters['per_page'] ?? 25)
            ->appends(request()->query());

        return Inertia::render('profiles/index', [
            'page' => [
                'data' => $data,
                'filters' => $filters ?: null,
            ],
        ]);
    }

    public function show($id)
    {
        $data = Profile::whereKey($id)->with(['listings'])->firstOrFail();

        return Inertia::render('profiles/show', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'slug' => 'nullable|string',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'percentage' => 'nullable|numeric|between:0,100',
        ]);

        // Generate slug if not provided
        $body = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($body['name'], '_');
        }

        Profile::create($data);

        return redirect()->route('profiles.index');

    }

    // Update an existing supplier (PUT)
    public function update(Request $request, $id)
    {
        // Find the supplier or return 404
        $supplier = Profile::find($id);
        if (! $supplier) {
            return response()->json(['message' => 'Fornitore non trovato.'], 404);
        }

        // Update the supplier
        $supplier->update($request->all());

        return redirect()->route('profiles.index');
    }

    // Delete supplier
    public function destroy(Profile $supplier)
    {
        $supplier->delete();

        return redirect()->route('profiles.index');
    }
}
