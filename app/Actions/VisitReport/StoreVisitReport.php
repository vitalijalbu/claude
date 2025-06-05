<?php

declare(strict_types=1);

namespace App\Actions\VisitReport;

use App\DTO\VisitReport\StoreVisitReportDto;
use App\Models\VisitReport;
use Illuminate\Validation\ValidationException;

class StoreVisitReport
{
    public function execute(StoreVisitReportDto $dto): VisitReport
    {
        // Replace by validation rule
        // // Check if visit already has a report
        // $existingReport = VisitReport::where('visit_id', $dto->visit_id)->first();

        // if ($existingReport) {
        //     throw ValidationException::withMessages([
        //         'visit_id' => 'This visit already has a report.',
        //     ]);
        // }

        return VisitReport::create($dto->toArray());
    }
}
