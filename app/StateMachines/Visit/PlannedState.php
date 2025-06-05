<?php

declare(strict_types=1);

namespace App\StateMachines\Visit;

use App\Enums\VisitStatus;
use App\Models\Visit;

class PlannedState extends BaseState
{
    public function confirm(): Visit
    {
        $this->visit->status = VisitStatus::CONFIRM_PLANNING;

        $this->visit->confirmed_at = now();
        // $this->visit->confirmed_by = auth()->guard()->id();

        $this->visit->save();

        return $this->visit;
    }

    public function confirmable(): bool
    {
        return true;
    }
}
