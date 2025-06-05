<?php

declare(strict_types=1);

namespace App\DTO\CapacityTest;

use Spatie\LaravelData\Data;

class StoreCapacityTestDto extends Data
{
    public function __construct(
        public string $supplier_id,
        public string $test_date,
        public ?string $result,
        public ?string $status,
        public ?string $test_deadline,
        public ?string $test_status,
        public ?string $product_type,
        public bool $send_product = false,
    ) {}
}
