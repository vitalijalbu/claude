<?php

namespace App\Http\Controllers;

use App\Actions\Category\IndexCategories;
use App\Actions\Category\ShowCategory;
use App\Actions\Category\StoreCategory;
use App\Actions\Category\UpdateCategory;
use App\DTO\Category\CategoryDTO;
use App\Http\Requests\Web\Category\StoreCategoryRequest;
use App\Http\Requests\Web\Category\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

final class CategoryController extends Controller
{
    public function index(Request $request, IndexCategories $action)
    {
        $filters = $request->all();
        $categories = $action->handle($filters);

        return Inertia::render('categories/index', [
            'categories' => $categories,
            'filters' => $filters,
        ]);
    }

    public function show(string $slug, ShowCategory $action)
    {
        $category = $action->handle($slug);

        return Inertia::render('categories/show', [
            'category' => $category,
        ]);
    }

    public function create()
    {
        return Inertia::render('categories/create');
    }

    public function store(StoreCategoryRequest $request, StoreCategory $action)
    {
        $dto = CategoryDTO::fromRequest($request->validated());
        $category = $action->handle($dto);

        return redirect()
            ->route('admin.categories.show', $category->slug)
            ->with('success', 'Categoria creata con successo.');
    }

    public function edit(string $slug, ShowCategory $action)
    {
        $category = $action->handle($slug);

        return Inertia::render('categories/edit', [
            'category' => $category,
        ]);
    }

    public function update(UpdateCategoryRequest $request, string $slug, UpdateCategory $action)
    {
        $dto = CategoryDTO::fromRequest($request->validated());
        $category = $action->handle($slug, $dto);

        return redirect()
            ->route('admin.categories.show', $category->slug)
            ->with('success', 'Categoria aggiornata con successo.');
    }
}
