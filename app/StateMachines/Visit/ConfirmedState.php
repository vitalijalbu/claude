<?php

declare(strict_types=1);

namespace App\StateMachines\Visit;

use App\Enums\VisitStatus;
use App\Models\Visit;

class ConfirmedState extends BaseState
{
    public function confirm(): Visit
    {
        return $this->visit;
    }

    public function review(): Visit
    {
        $this->visit->status = VisitStatus::TO_REVIEW;

        $this->visit->review_requested_at = now();
        $this->visit->review_requested_by = auth()->guard('atlas')->user()->id;

        $this->visit->save();

        return $this->visit;
    }

    public function confirmable(): bool
    {
        return true;
    }

    public function reviewable(): bool
    {
        return true;
    }

    public function testableForCapacity(): bool
    {
        return true;
    }
}
