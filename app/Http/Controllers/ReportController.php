<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\VisitReport\DestroyVisitReport;
use App\Actions\VisitReport\IndexVisitReports;
use App\Actions\VisitReport\ShowVisitReport;
use App\Actions\VisitReport\StoreVisitReport;
use App\Actions\VisitReport\UpdateVisitReport;
use App\DTO\VisitReport\IndexVisitReportsDto;
use App\DTO\VisitReport\StoreVisitReportDto;
use App\DTO\VisitReport\UpdateVisitReportDto;
use App\Http\Requests\VisitReport\IndexVisitReportsRequest;
use App\Http\Requests\VisitReport\StoreVisitReportRequest;
use App\Http\Requests\VisitReport\UpdateVisitReportRequest;
use App\Http\Resources\VisitReportResource;
use App\Models\Supplier;
use App\Models\Visit;
use App\Models\VisitReport;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

final class ReportController extends Controller
{
    /**
     * Display all visit reports.
     *
     * @response AnonymousResourceCollection<LengthAwarePaginator<VisitReportResource>>
     */
    public function index(IndexVisitReportsRequest $request, Supplier $supplier, IndexVisitReports $action): AnonymousResourceCollection
    {
        $data = $action->execute(dto: IndexVisitReportsDto::from($request->validated()));

        return VisitReportResource::collection($data);
    }

    // /**
    //  * Display the specified visit report.
    //  */
    // public function show(VisitReport $report, ShowVisitReport $action): VisitReportResource
    // {
    //     return $action->execute($report);
    // }

    // /**
    //  * Store a new visit report.
    //  */
    // public function store(StoreVisitReportRequest $request, Visit $visit, StoreVisitReport $action): VisitReportResource
    // {
    //     $validated = $request->validated();
    //     $validated['visit_id'] = $visit->id;

    //     $report = $action->execute(StoreVisitReportDto::from($validated));

    //     return new VisitReportResource($report);
    // }

    // /**
    //  * Update the specified visit report.
    //  */
    // public function update(UpdateVisitReportRequest $request, VisitReport $report, UpdateVisitReport $action): VisitReportResource
    // {
    //     $updatedReport = $action->execute($report, UpdateVisitReportDto::from($request->validated())->toArray());

    //     return new VisitReportResource($updatedReport);
    // }

    // /**
    //  * Remove the specified visit report.
    //  */
    // public function destroy(VisitReport $report, DestroyVisitReport $action): Response
    // {
    //     $action->execute($report);

    //     return response()->noContent();
    // }

    public function store(
        StoreVisitReportRequest $request,
        Visit $visit,
        StoreVisitReport $action
    ): VisitReportResource {
        $report = $action->execute(StoreVisitReportDto::from($request->validated()));

        return VisitReportResource::make($report);
    }

    public function show(
        Visit $visit,
        ShowVisitReport $action
    ): ?VisitReportResource {
        return $visit->report ? $action->execute($visit->report) : null;
    }

    public function update(
        UpdateVisitReportRequest $request,
        Visit $visit,
        UpdateVisitReport $action
    ): VisitReportResource {
        $updatedReport = $action->execute($visit->report, $request->validated());

        return VisitReportResource::make($updatedReport);
    }
}
