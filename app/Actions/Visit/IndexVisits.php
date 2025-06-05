<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\DTO\Visit\IndexVisitsDto;
use App\Models\Visit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class IndexVisits
{
    public function execute(IndexVisitsDto $dto): LengthAwarePaginator
    {
        return Visit::query()
            ->with([
                'supplier',
                'creator',
                'inspector',
            ])
            ->leftJoin('suppliers', 'technical_visits.supplier_id', '=', 'suppliers.id')
            ->when($dto->status, fn (Builder $query) => $query->where('technical_visits.status', $dto->status))
            ->when($dto->date_from, fn (Builder $query) => $query->whereDate('technical_visits.created_at', '>=', $dto->date_from))
            ->when($dto->date_to, fn (Builder $query) => $query->whereDate('technical_visits.created_at', '<=', $dto->date_to))
            ->when($dto->supplier?->id, fn (Builder $query) => $query->where('technical_visits.supplier_id', $dto->supplier->id))
            ->when($dto->supplier?->name, fn (Builder $query) => $query->where('suppliers.name', 'like', '%' . $dto->supplier->name . '%'))
            ->when($dto->supplier?->ympact_id, fn (Builder $query) => $query->where('suppliers.ympact_id', $dto->supplier->ympact_id))
            ->when($dto->supplier?->priority, fn (Builder $query) => $query->where('suppliers.priority', $dto->supplier->priority))
            ->when($dto->supplier?->status, fn (Builder $query) => $query->where('suppliers.status', $dto->supplier->status))
            ->when(
                $dto->sort_by ?? null,
                fn (Builder $query) => $query->orderBy($dto->sort_by, $dto->sort_direction ?? 'asc'),
                fn (Builder $query) => $query->orderBy('suppliers.name', 'asc')
            )
            ->select('technical_visits.*')
            ->paginate(
                perPage: $dto->per_page ?? 10,
                page: $dto->page ?? 1
            );
    }
}
