<?php

declare(strict_types=1);

namespace App\DTO\Supplier;

use Spatie\LaravelData\Data;

class IndexSupplierVisitsDto extends Data
{
    public function __construct(
        public ?string $supplier_id,
        public ?int $page = 1,
        public ?int $per_page = 15,
    ) {}
}
