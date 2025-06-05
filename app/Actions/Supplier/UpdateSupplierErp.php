<?php

declare(strict_types=1);

namespace App\Actions\Supplier;

use App\DTO\Supplier\UpdateSupplierErpDto;
use App\Models\SupplierData;

class UpdateSupplierErp
{
    public function execute(UpdateSupplierErpDto $dto): SupplierData
    {
        return SupplierData::updateOrCreate(
            ['supplier_id' => $dto->supplier_id],
            [
                'email' => $dto->email,
                'phone' => $dto->phone,
                'sent_to_erp' => $dto->sent_to_erp,
                'data_sent_to_erp' => $dto->data_sent_to_erp,
            ]
        );
    }
}
