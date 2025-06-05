<?php

declare(strict_types=1);

namespace App\DTO\Visit;

use Spatie\LaravelData\Data;

class SupplierDto extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $name,
        public ?string $ympact_id,
        public ?string $status,
        public ?string $priority,
        public ?string $country,
        public ?string $city,
        public ?string $address,
        public ?string $pre_assessment_score,
    ) {}
}
