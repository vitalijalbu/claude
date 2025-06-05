<?php

declare(strict_types=1);

namespace App\Actions\VisitReport;

use App\Models\VisitReport;

class DestroyVisitReport
{
    public function execute(VisitReport $visitReport): bool
    {
        return $visitReport->delete();
    }
}
