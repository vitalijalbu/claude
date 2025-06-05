<?php

declare(strict_types=1);

namespace App\StateMachines\Visit;

use App\Enums\VisitStatus;
use App\Models\Visit;

class RejectedState extends BaseState
{
    public function reject(): Visit
    {
        $this->visit->status = VisitStatus::REJECTED;

        // Optionally, you can set rejection details
        // $this->visit->rejected_at = now();
        // $this->visit->rejected_by = auth()->guard()->id();

        $this->visit->save();

        return $this->visit;
    }
}
