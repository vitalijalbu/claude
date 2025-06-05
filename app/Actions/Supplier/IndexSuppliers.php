<?php

declare(strict_types=1);

namespace App\Actions\Supplier;

use App\Filters\SupplierFilters;
use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class IndexSuppliers
{
    public function execute(array $filters = []): LengthAwarePaginator
    {
        return QueryBuilder::for(Supplier::class)
            ->allowedFilters(SupplierFilters::getAllowedFilters())
            ->allowedSorts(SupplierFilters::getSortableFields())
            ->allowedIncludes(SupplierFilters::getIncludableRelations())
            ->defaultSort('-created_at')
            ->paginate($filters['per_page'] ?? 15)
            ->appends(request()->query());
    }
}
