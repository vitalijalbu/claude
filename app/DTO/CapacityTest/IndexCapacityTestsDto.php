<?php

declare(strict_types=1);

namespace App\DTO\CapacityTest;

use Spatie\LaravelData\Data;

class IndexCapacityTestsDto extends Data
{
    public function __construct(
        public ?string $status,
        public ?string $result,
        public ?string $supplier_id,
        public ?string $date_from,
        public ?string $date_to,
        public ?string $product_type,
        public ?string $sort_by,
        public ?string $sort_direction,
        public ?int $page = 1,
        public ?int $per_page = 15,
    ) {}
}
