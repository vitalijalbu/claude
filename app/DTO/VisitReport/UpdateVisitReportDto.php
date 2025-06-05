<?php

declare(strict_types=1);

namespace App\DTO\VisitReport;

use Spatie\LaravelData\Data;

class UpdateVisitReportDto extends Data
{
    public function __construct(
        public ?int $version,
        public ?string $content,
        public ?bool $is_extended_from_test,
        public ?string $technical_skills,
        public ?string $technical_skills_note,
        public ?string $production_times_capacity,
        public ?string $production_times_capacity_note,
        public ?string $suitable_for,
        public ?string $suitable_for_note,
        public ?string $innovation_level,
        public ?string $innovation_level_note,
        public ?string $technical_result,
        public ?string $comment,
    ) {}
}
