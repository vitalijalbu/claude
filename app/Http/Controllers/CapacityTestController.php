<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CapacityTest\DestroyCapacityTest;
use App\Actions\CapacityTest\ExportCapacityTests;
use App\Actions\CapacityTest\IndexCapacityTests;
use App\Actions\CapacityTest\ShowCapacityTest;
use App\Actions\CapacityTest\StoreCapacityTest;
use App\Actions\CapacityTest\UpdateCapacityTest;
use App\DTO\CapacityTest\IndexCapacityTestsDto;
use App\DTO\CapacityTest\StoreCapacityTestDto;
use App\DTO\CapacityTest\UpdateCapacityTestDto;
use App\Http\Requests\CapacityTest\IndexCapacityTestsRequest;
use App\Http\Requests\CapacityTest\StoreCapacityTestRequest;
use App\Http\Requests\CapacityTest\UpdateCapacityTestRequest;
use App\Http\Resources\CapacityTestResource;
use App\Models\CapacityTest;
use App\Models\Supplier;
use App\XLSX\CapacityTestsExport;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class CapacityTestController extends Controller
{
    /**
     * Display all capacity tests.
     *
     * @response AnonymousResourceCollection<LengthAwarePaginator<CapacityTestResource>>
     */
    public function index(IndexCapacityTestsRequest $request, IndexCapacityTests $action): AnonymousResourceCollection
    {
        $data = $action->execute(dto: IndexCapacityTestsDto::from($request->validated()));

        return CapacityTestResource::collection($data);
    }

    /**
     * Display the specified capacity test.
     */
    public function show(CapacityTest $capacityTest, ShowCapacityTest $action): CapacityTestResource
    {
        return $action->execute($capacityTest);
    }

    /**
     * Store a new capacity test.
     */
    public function store(StoreCapacityTestRequest $request, Supplier $supplier, StoreCapacityTest $action): CapacityTestResource
    {
        $validated = $request->validated();
        $validated['supplier_id'] = $supplier->id;

        $capacityTest = $action->execute(StoreCapacityTestDto::from($validated));

        return new CapacityTestResource($capacityTest);
    }

    /**
     * Update the specified capacity test.
     */
    public function updatePlanning(UpdateCapacityTestRequest $request, CapacityTest $capacityTest, UpdateCapacityTest $action): CapacityTestResource
    {
        $updatedTest = $action->execute($capacityTest, UpdateCapacityTestDto::from($request->validated()));

        return new CapacityTestResource($updatedTest);
    }

    /**
     * Update the result of capacity test.
     */
    public function updateResults(UpdateCapacityTestRequest $request, CapacityTest $capacityTest, UpdateCapacityTest $action): CapacityTestResource
    {
        $updatedTest = $action->execute($capacityTest, UpdateCapacityTestDto::from($request->validated()));

        return new CapacityTestResource($updatedTest);
    }

    /**
     * Remove the specified capacity test.
     */
    public function destroy(CapacityTest $capacityTest, DestroyCapacityTest $action): Response
    {
        $action->execute($capacityTest);

        return response()->noContent();
    }

    /**
     * Export all capacityTests.
     */
    public function export(ExportCapacityTests $action): StreamedResponse
    {
        $capacityTests = $action->execute();

        return (new CapacityTestsExport($capacityTests))->download('capacity-tests-export.xlsx');
    }
}
