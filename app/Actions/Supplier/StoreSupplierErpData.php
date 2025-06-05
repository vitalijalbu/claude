<?php

declare(strict_types=1);

namespace App\Actions\Supplier;

use App\DTO\Supplier\StoreSupplierDto;
use App\Models\Supplier;

class StoreSupplierErpData
{
    public function execute(StoreSupplierDto $dto): Supplier
    {
        return Supplier::create($dto->toArray());
    }
}
