<?php

declare(strict_types=1);

namespace App\Actions\Supplier;

use App\Http\Resources\SupplierResource;
use App\Models\Supplier;

class ShowErpDataSupplier
{
    public function execute(Supplier $supplier): SupplierResource
    {
        $supplier->load(['erpData']);

        return new SupplierResource($supplier);
    }
}
