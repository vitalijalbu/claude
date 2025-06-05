<?php

declare(strict_types=1);

namespace App\StateMachines\Visit;

use App\Enums\VisitStatus;
use App\Models\Visit;

class ReviewState extends BaseState
{
    public function review(): Visit
    {
        return $this->visit;
    }

    public function complete(): Visit
    {
        $this->visit->status = VisitStatus::COMPLETED;

        $this->visit->completed_at = now();
        $this->visit->completed_by = auth()->guard()->user()->id;

        $this->visit->save();

        return $this->visit;
    }

    public function completable(): bool
    {
        return true;
    }

    public function requestCapacityTest(): Visit
    {
        $this->visit->status = VisitStatus::TEST_NEEDED;
        $this->visit->capacity_requested_at = now();
        $this->visit->capacity_requested_by = auth()->guard()->user()->id;

        $this->visit->save();

        return $this->visit;
    }

    public function testableForCapacity(): bool
    {
        return true;
    }
}
