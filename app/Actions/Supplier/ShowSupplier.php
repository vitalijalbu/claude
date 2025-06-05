<?php

declare(strict_types=1);

namespace App\Actions\Supplier;

use App\Http\Resources\SupplierResource;
use App\Models\Supplier;

class ShowSupplier
{
    public function execute(Supplier $supplier): SupplierResource
    {
        $supplier->load([
            'lastVisit',
            'lastVisit.report',
            'lastVisit.inspector',
            'erpData'
        ]);

        return new SupplierResource($supplier);
    }
}
