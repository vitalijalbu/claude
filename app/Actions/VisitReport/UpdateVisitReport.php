<?php

declare(strict_types=1);

namespace App\Actions\VisitReport;

use App\Models\VisitReport;

class UpdateVisitReport
{
    public function execute(VisitReport $visitReport, array $data): VisitReport
    {
        $visitReport->update($data);

        return $visitReport->fresh();
    }
}
