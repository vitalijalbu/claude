<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\Http\Resources\VisitResource;
use App\Models\Visit;

class ShowVisit
{
    public function execute(Visit $visit): VisitResource
    {
        $visit->load(['supplier', 'report', 'inspector', 'creator']);

        return new VisitResource($visit);
    }
}
