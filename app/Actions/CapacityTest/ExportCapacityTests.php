<?php

declare(strict_types=1);

namespace App\Actions\CapacityTest;

use App\Models\CapacityTest;
use Illuminate\Database\Eloquent\Collection;

class ExportCapacityTests
{
    public function execute(): Collection
    {
        return CapacityTest::query()
            ->with([
                'supplier',
                'reports',
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
