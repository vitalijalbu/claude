<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Visit\AddVisitWarning;
use App\Actions\Visit\IndexVisits;
use App\Actions\Visit\ShowVisit;
use App\Actions\Visit\StoreVisit;
use App\Actions\Visit\UpdateVisit;
use App\Actions\Visit\UpdateVisitStatus;
use App\DTO\Visit\IndexVisitsDto;
use App\Http\Requests\Visit\AddVisitWarningRequest;
use App\Http\Requests\Visit\IndexVisitsRequest;
use App\Http\Requests\Visit\StoreVisitRequest;
use App\Http\Requests\Visit\UpdateVisitRequest;
use App\Http\Requests\Visit\UpdateVisitStatusRequest;
use App\Http\Resources\VisitResource;
use App\Models\Visit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

final class VisitController extends Controller
{
    /**
     * Display all visits
     *
     * @response AnonymousResourceCollection<LengthAwarePaginator<VisitResource>>
     */
    public function index(IndexVisitsRequest $request, IndexVisits $action): AnonymousResourceCollection
    {
        $data = $action->execute(dto: IndexVisitsDto::from($request->validated()));

        return VisitResource::collection($data);
    }

    /**
     * Display the specified technical visit.
     */
    public function show(Visit $visit, ShowVisit $action): VisitResource
    {
        return $action->execute($visit);
    }

    /**
     * Store a new technical visit.
     *
     * @response \Illuminate\Http\JsonResponse<array{
     *     message: string,
     *     total: int,
     *     data: \Illuminate\Http\Resources\Json\AnonymousResourceCollection<VisitResource>
     * }>
     */
    public function store(StoreVisitRequest $request, StoreVisit $action): JsonResponse
    {
        $visits = $action->execute($request->validated());

        return response()->json([
            'message' => 'Visits created successfully',
            'total' => $visits->count(),
            'data' => VisitResource::collection($visits),
        ], 201);
    }

    /**
     * Update the specified technical visit.
     */
    public function update(UpdateVisitRequest $request, Visit $visit, UpdateVisit $action): VisitResource
    {
        $updatedVisit = $action->execute($visit, $request->validated());

        return VisitResource::make($updatedVisit);
    }

    /**
     * Update the status of technical visit.
     */
    public function updateStatus(UpdateVisitStatusRequest $request, Visit $visit, UpdateVisitStatus $action): VisitResource
    {
        $updatedVisit = $action->execute($visit, $request->validated('status'));

        return VisitResource::make($updatedVisit);
    }

    public function confirm(Request $request, Visit $visit): VisitResource
    {
        Gate::authorize('confirm', $visit);

        $updatedVisit = $visit->state()->confirm();

        return VisitResource::make($updatedVisit);
    }

    public function review(Request $request, Visit $visit): VisitResource
    {
        Gate::authorize('review', $visit);

        $updatedVisit = $visit->state()->review();

        return VisitResource::make($updatedVisit);
    }

    public function requestCapacityTest(Request $request, Visit $visit): VisitResource
    {
        Gate::authorize('requestCapacityTest', $visit);

        $updatedVisit = $visit->state()->requestCapacityTest();

        return VisitResource::make($updatedVisit);
    }

    /**
     * Add warning to specified technical visit.
     */
    public function addWarning(AddVisitWarningRequest $request, Visit $visit, AddVisitWarning $action): VisitResource
    {
        $updatedVisit = $action->execute(
            $visit,
            $request->validated('critical_issue'),
            $request->boolean('send_alert', false)
        );

        return VisitResource::make($updatedVisit);
    }
}
