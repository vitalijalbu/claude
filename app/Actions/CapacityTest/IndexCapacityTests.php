<?php

declare(strict_types=1);

namespace App\Actions\CapacityTest;

use App\DTO\CapacityTest\IndexCapacityTestsDto;
use App\Models\CapacityTest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class IndexCapacityTests
{
    public function execute(IndexCapacityTestsDto $dto): LengthAwarePaginator
    {
        return CapacityTest::query()
            ->with(['supplier', 'creator'])
            ->leftJoin('suppliers', 'production_capacity_tests.supplier_id', '=', 'suppliers.id')
            ->when($dto->status, fn (Builder $query) => $query->where('production_capacity_tests.status', $dto->status))
            ->when($dto->result, fn (Builder $query) => $query->where('production_capacity_tests.result', $dto->result))
            ->when($dto->supplier_id, fn (Builder $query) => $query->where('production_capacity_tests.supplier_id', $dto->supplier_id))
            ->when($dto->product_type, fn (Builder $query) => $query->where('production_capacity_tests.product_type', 'like', '%' . $dto->product_type . '%'))
            ->when($dto->date_from, fn (Builder $query) => $query->whereDate('production_capacity_tests.test_date', '>=', $dto->date_from))
            ->when($dto->date_to, fn (Builder $query) => $query->whereDate('production_capacity_tests.test_date', '<=', $dto->date_to))
            ->when(
                $dto->sort_by ?? null,
                fn (Builder $query) => $query->orderBy($dto->sort_by, $dto->sort_direction ?? 'asc'),
                fn (Builder $query) => $query->orderBy('production_capacity_tests.created_at', 'desc')
            )
            ->select('production_capacity_tests.*')
            ->paginate(
                perPage: $dto->per_page ?? 10,
                page: $dto->page ?? 1
            );
    }
}
