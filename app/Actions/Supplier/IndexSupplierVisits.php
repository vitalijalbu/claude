<?php

declare(strict_types=1);

namespace App\Actions\Supplier;

use App\Models\Supplier;
use App\Models\Visit;

class IndexSupplierVisits
{
    public function execute(Supplier $supplier)
    {
        return Visit::query()
            ->with([
                'supplier',
                'report',
                'creator',
            ])
            ->where('supplier_id', $supplier->id)
            ->orderByDesc('created_at')
            ->get();
    }
}
