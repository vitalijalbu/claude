<?php

declare(strict_types=1);

namespace App\DTO\VisitReport;

use Spatie\LaravelData\Data;

class StoreVisitReportDto extends Data
{
    public function __construct(
        public ?string $visit_id,
        public ?string $production_test_id,
        public int $version,
        public ?string $content,
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
        public bool $is_extended_from_test = false,
    ) {}
}
