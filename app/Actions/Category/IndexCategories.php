<?php

declare(strict_types=1);

namespace App\Actions\Category;

use App\DTO\Category\CategoryFilterDTO;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class IndexCategories
{
    public function handle(?CategoryFilterDTO $filters = null): LengthAwarePaginator
    {
        $query = QueryBuilder::for(Category::class)
            ->allowedFilters(['name', 'slug'])
            ->allowedSorts(['name', 'slug', 'created_at'])
            ->defaultSort('name');

        if ($filters) {
            if ($filters->search) {
                $query->where('name', 'LIKE', "%{$filters->search}%");
            }

            return $query
                ->orderBy($filters->sort, $filters->direction)
                ->paginate($filters->per_page, ['*'], 'page', $filters->page);
        }

        return $query->get();
    }
}
