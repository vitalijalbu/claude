<?php

declare(strict_types=1);

namespace App\Actions\CapacityTest;

use App\DTO\CapacityTest\StoreCapacityTestDto;
use App\Models\CapacityTest;
use App\Models\User;

class StoreCapacityTest
{
    public function execute(StoreCapacityTestDto $dto): CapacityTest
    {
        return CapacityTest::create([
            ...$dto->toArray(),
            'created_by' => $this->resolveRandomUserId(),
        ]);
    }

    private function resolveRandomUserId(): string
    {
        return User::query()
            ->inRandomOrder()
            ->first()
            ->id;
    }
}
