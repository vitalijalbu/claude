<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->query();
        $data = QueryBuilder::for(Image::class)
            ->paginate($filters['per_page'] ?? 25)
            ->appends(request()->query());

        return Inertia::render('media/index', [
            'page' => [
                'data' => $data,
                'filters' => $filters ?: null,
            ],
        ]);
    }

    public function show($id)
    {
        $data = Image::whereKey($id)->firstOrFail();

        return Inertia::render('media/show', ['data' => $data]);
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

        Listing::create($data);

        return redirect()->route('media.index');

    }

    // Update an existing supplier (PUT)
    public function update(Request $request, $id)
    {
        // Find the supplier or return 404
        $supplier = Listing::find($id);
        if (! $supplier) {
            return response()->json(['message' => 'Fornitore non trovato.'], 404);
        }

        // Update the supplier
        $supplier->update($request->all());

        return redirect()->route('media.index');
    }

    // Delete supplier
    public function destroy(Listing $supplier)
    {
        $supplier->delete();

        return redirect()->route('media.index');
    }
}
