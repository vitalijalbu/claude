<?php

declare(strict_types=1);

namespace App\DTO\VisitReport;

use App\DTO\Visit\SupplierDto;
use Spatie\LaravelData\Data;

class IndexVisitReportsDto extends Data
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
