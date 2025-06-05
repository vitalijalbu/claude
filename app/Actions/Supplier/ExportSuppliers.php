<?php

declare(strict_types=1);

namespace App\Actions\Supplier;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;

class ExportSuppliers
{
    public function execute(): Collection
    {
        return Supplier::query()
            ->with([
                'organization',
                'lastVisit',
                'erpData',
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
