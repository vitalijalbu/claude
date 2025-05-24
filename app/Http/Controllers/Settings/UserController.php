<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->query();
        $data = QueryBuilder::for(User::class)
            ->with(['roles'])
            ->allowedFilters(['name', 'email'])
            ->allowedSorts(['name', 'email'])
            ->paginate($filters['per_page'] ?? 25)
            ->appends(request()->query());

        return Inertia::render('settings/users/index', [
            'page' => [
                'data' => $data,
                'filters' => $filters ?: null,
            ],
        ]);
    }

    public function show($id)
    {
        $data = User::whereKey($id)->firstOrFail();

        return Inertia::render('settings/users/show', ['data' => $data]);
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

        User::create($data);

        return redirect()->route('settings/users/index');

    }

    // Update an existing supplier (PUT)
    public function update(Request $request, $id)
    {
        // Find the supplier or return 404
        $supplier = User::find($id);
        if (! $supplier) {
            return response()->json(['message' => 'Fornitore non trovato.'], 404);
        }

        // Update the supplier
        $supplier->update($request->all());

        return redirect()->route('settings/users/index');
    }

    // Delete supplier
    public function destroy(User $supplier)
    {
        $supplier->delete();

        return redirect()->route('settings/users/index');
    }
}
