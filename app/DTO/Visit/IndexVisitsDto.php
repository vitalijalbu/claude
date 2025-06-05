<?php

declare(strict_types=1);

namespace App\DTO\Visit;

use Spatie\LaravelData\Data;

class IndexVisitsDto extends Data
{
    public function __construct(
        public ?string $status,
        public ?string $date_from,
        public ?string $date_to,
        public ?SupplierDto $supplier,
        public ?string $sort_by,
        public ?string $sort_direction,
        public ?int $page = 1,
        public ?int $per_page = 15,
    ) {}
}
