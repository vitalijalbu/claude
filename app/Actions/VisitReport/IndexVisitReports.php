<?php

declare(strict_types=1);

namespace App\Actions\VisitReport;

use App\DTO\VisitReport\IndexVisitReportsDto;
use App\Models\VisitReport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class IndexVisitReports
{
    public function execute(IndexVisitReportsDto $dto): LengthAwarePaginator
    {
        return VisitReport::query()
            ->with([
                'visit.supplier',
                'productionTest.supplier',
                'creator',
            ])
            ->leftJoin('technical_visits', 'visit_reports.visit_id', '=', 'technical_visits.id')
            ->leftJoin('production_capacity_tests', 'visit_reports.production_test_id', '=', 'production_capacity_tests.id')
            ->leftJoin('suppliers as visit_suppliers', 'technical_visits.supplier_id', '=', 'visit_suppliers.id')
            ->leftJoin('suppliers as test_suppliers', 'production_capacity_tests.supplier_id', '=', 'test_suppliers.id')
            ->when($dto->status, fn (Builder $query) => $query->where('technical_visits.status', $dto->status))
            ->when($dto->date_from, fn (Builder $query) => $query->whereDate('visit_reports.created_at', '>=', $dto->date_from))
            ->when($dto->date_to, fn (Builder $query) => $query->whereDate('visit_reports.created_at', '<=', $dto->date_to))
            ->when($dto->supplier?->id, function (Builder $query) use ($dto) {
                $query->where(function ($q) use ($dto) {
                    $q->where('visit_suppliers.id', $dto->supplier->id)
                        ->orWhere('test_suppliers.id', $dto->supplier->id);
                });
            })
            ->when($dto->supplier?->name, function (Builder $query) use ($dto) {
                $query->where(function ($q) use ($dto) {
                    $q->where('visit_suppliers.name', 'like', '%' . $dto->supplier->name . '%')
                        ->orWhere('test_suppliers.name', 'like', '%' . $dto->supplier->name . '%');
                });
            })
            ->when(
                $dto->sort_by ?? null,
                fn (Builder $query) => $query->orderBy($dto->sort_by, $dto->sort_direction ?? 'asc'),
                fn (Builder $query) => $query->orderBy('visit_reports.created_at', 'desc')
            )
            ->select('visit_reports.*')
            ->paginate(
                perPage: $dto->per_page ?? 10,
                page: $dto->page ?? 1
            );
    }
}
