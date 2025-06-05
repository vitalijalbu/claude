<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Visit;

class VisitPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        // Temporarily allow all actions for all users
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Visit $visit): bool
    {
        return false;
    }

    public function destroy(User $user, Visit $visit): bool
    {
        return false;
    }

    public function confirm(User $user, Visit $visit)
    {

        return false;
    }

    public function requestCapacityTest(User $user, Visit $visit)
    {

        return false;
    }

    public function review(User $user, Visit $visit)
    {

        return false;
    }

    public function complete(User $user, Visit $visit)
    {

        return false;
    }

    public function reject(User $user, Visit $visit)
    {

        return false;
    }
}
