<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Supplier\ExportSuppliers;
use App\Actions\Supplier\IndexSuppliers;
use App\Actions\Supplier\IndexSupplierVisits;
use App\Actions\Supplier\ShowSupplier;
use App\Actions\Supplier\UpdateSupplierErp;
use App\DTO\Supplier\UpdateSupplierErpDto;
use App\Http\Requests\Supplier\IndexSuppliersRequest;
use App\Http\Requests\Supplier\UpdateSupplierErpRequest;
use App\Http\Resources\SupplierResource;
use App\Http\Resources\VisitResource;
use App\Models\Supplier;
use App\XLSX\SuppliersExport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class SupplierController extends Controller
{
    /**
     * Display all suppliers
     *
     * @response AnonymousResourceCollection<LengthAwarePaginator<SupplierResource>>
     */
    public function index(IndexSuppliersRequest $request, IndexSuppliers $action): AnonymousResourceCollection
    {
        $data = $action->execute($request->validated());

        return SupplierResource::collection($data);
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier, ShowSupplier $action): SupplierResource
    {
        return $action->execute($supplier);
    }

    /**
     * Update ERP DATA of specified supplier.
     */
    public function updateErpData(UpdateSupplierErpRequest $request, UpdateSupplierErp $action): JsonResponse
    {
        $supplierData = $action->execute(UpdateSupplierErpDto::from($request->validated()));

        return response()->json([
            'message' => 'ERP data updated successfully',
            'data' => $supplierData,
        ]);
    }

    /**
     * Display all visits of specified supplier.
     */
    public function visits(Supplier $supplier, IndexSupplierVisits $action)
    {
        $visits = $action->execute($supplier);

        return VisitResource::collection($visits);
    }

    /**
     * Export all suppliers.
     */
    public function export(ExportSuppliers $action): StreamedResponse
    {
        $suppliers = $action->execute();

        return (new SuppliersExport($suppliers))->download('suppliers-export.xlsx');
    }
}
