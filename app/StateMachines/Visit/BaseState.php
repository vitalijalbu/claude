<?php

declare(strict_types=1);

namespace App\StateMachines\Visit;

use App\Exceptions\VisitStateMachineException;
use App\Models\Visit;

abstract class BaseState
{
    public function __construct(public Visit $visit) {}

    /**
     * move to confirm state
     */
    public function confirm(): Visit
    {
        throw new VisitStateMachineException('Cannot move to confirm.');
    }

    /**
     * move to review state
     */
    public function review(): Visit
    {
        throw new VisitStateMachineException('Cannot move to confirm.');
    }

    /**
     * move to complete state
     */
    public function complete(): Visit
    {

        throw new VisitStateMachineException('Cannot move to confirm.');
    }

    /**
     * move to requestCapacityTest state
     */
    public function requestCapacityTest(): Visit
    {
        throw new VisitStateMachineException('Cannot move to confirm.');
    }

    /**
     * could be confirmed
     */
    public function confirmable(): bool
    {
        return false;
    }

    /**
     * could be reviewed
     */
    public function reviewable(): bool
    {
        return false;
    }

    /**
     * could be completed
     */
    public function completable(): bool
    {
        return false;
    }

    /**
     * Could request a capacityTest
     */
    public function testableForCapacity(): bool
    {
        return false;
    }

    public function reject(): Visit
    {
        throw new VisitStateMachineException('Cannot reject visit in this state.');
    }

    public function rejectable(): bool
    {
        return false;
    }
}
