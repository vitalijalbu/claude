<?php

declare(strict_types=1);

namespace App\StateMachines\Visit;

use App\Enums\VisitStatus;
use App\Models\Visit;

class TestNeededState extends BaseState
{
    public function requestCapacityTest(): Visit
    {
        return $this->visit;
    }

    public function complete(): Visit
    {
        $this->visit->status = VisitStatus::COMPLETED;

        $this->visit->completed_at = now();
        $this->visit->completed_by = auth()->guard()->id();

        $this->visit->save();

        return $this->visit;
    }

    public function completable(): bool
    {
        return true;
    }
}
