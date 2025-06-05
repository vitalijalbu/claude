<?php

declare(strict_types=1);

namespace App\Actions\VisitReport;

use App\Http\Resources\VisitReportResource;
use App\Models\VisitReport;

class ShowVisitReport
{
    public function execute(VisitReport $visitReport): VisitReportResource
    {
        $visitReport->load(['visit.supplier', 'productionTest.supplier', 'creator']);

        return new VisitReportResource($visitReport);
    }
}
