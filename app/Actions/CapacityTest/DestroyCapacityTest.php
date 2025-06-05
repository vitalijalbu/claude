<?php

declare(strict_types=1);

namespace App\Actions\CapacityTest;

use App\Models\CapacityTest;

class DestroyCapacityTest
{
    public function execute(CapacityTest $capacityTest): bool
    {
        return $capacityTest->delete();
    }
}
