<?php

declare(strict_types=1);

namespace App\Actions\CapacityTest;

use App\DTO\CapacityTest\UpdateCapacityTestDto;
use App\Models\CapacityTest;

class UpdateCapacityTest
{
    public function execute(CapacityTest $capacityTest, UpdateCapacityTestDto $dto): CapacityTest
    {
        $capacityTest->update($dto->toArray());

        return $capacityTest->fresh();
    }
}
