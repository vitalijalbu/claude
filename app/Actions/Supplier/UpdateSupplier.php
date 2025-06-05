<?php

declare(strict_types=1);

namespace App\Actions\Supplier;

use App\DTO\Supplier\UpdateSupplierDto;
use App\Models\Supplier;

class UpdateSupplier
{
    public function execute(Supplier $supplier, UpdateSupplierDto $dto): Supplier
    {
        $supplier->update($dto->toArray());

        return $supplier;
    }
}
