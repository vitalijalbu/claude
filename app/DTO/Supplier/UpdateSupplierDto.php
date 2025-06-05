<?php

declare(strict_types=1);

namespace App\DTO\Supplier;

use Spatie\LaravelData\Data;

class UpdateSupplierDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $ympact_id,
        public ?string $organization_id,
        public ?string $name,
        public ?string $address,
        public ?string $vat,
        public ?string $email,
        public ?string $phone,
        public ?string $country,
        public ?string $province,
        public ?string $city,
        public ?string $postal_code,
        public ?string $status,
        public ?string $pre_assessment_score,
        public ?string $pre_assessment_date,
        public ?string $priority,
    ) {}
}
