<?php

declare(strict_types=1);

namespace App\DTO\Visit;

use Spatie\LaravelData\Data;

class StoreVisitDto extends Data
{
    public function __construct(
        public string $supplier_id,
        public ?string $date,
        public string $inspector_atlas_id,
        public string $inspector_name,
        public string $inspector_email,
        public ?string $feedback,
        public ?string $result,
        public ?string $certificate,
        public ?bool $has_warning,
        public ?string $critical_issue,
    ) {}
}
