<?php

declare(strict_types=1);

namespace App\DTO\Supplier;

use Spatie\LaravelData\Data;

class UpdateSupplierErpDto extends Data
{
    public function __construct(
        public string $supplier_id,
        public ?string $email,
        public ?string $phone,
        public ?string $sent_to_erp,
        public ?string $data_sent_to_erp,
    ) {}
}
