<?php

declare(strict_types=1);

namespace App\Actions\CapacityTest;

use App\Http\Resources\CapacityTestResource;
use App\Models\CapacityTest;

class ShowCapacityTest
{
    public function execute(CapacityTest $capacityTest): CapacityTestResource
    {
        $capacityTest->load(['supplier', 'creator']);

        return new CapacityTestResource($capacityTest);
    }
}
