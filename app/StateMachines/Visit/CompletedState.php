<?php

declare(strict_types=1);

namespace App\StateMachines\Visit;

use App\Models\Visit;

class CompletedState extends BaseState
{
    public function complete(): Visit
    {
        return $this->visit;
    }
}
