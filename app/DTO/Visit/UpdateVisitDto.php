<?php

declare(strict_types=1);

namespace App\DTO\Visit;

use Spatie\LaravelData\Data;

class UpdateVisitDto extends Data
{
    public function __construct(
        public ?string $date,
        public ?string $inspector_id,
        public ?string $inspector_name,
        public ?string $inspector_email,
        public ?string $feedback,
        public ?string $result,
        public ?string $status,
        public ?string $certificate,
        public ?bool $has_warning,
        public ?string $critical_issue,
    ) {}
}
