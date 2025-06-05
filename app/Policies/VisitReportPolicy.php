<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Visit;
use App\Models\VisitReport;

class VisitReportPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function before(User $user, string $ability): ?bool
    {
        return true;
    }

    public function create(User $user, Visit $visit): bool
    {
        if ($user->hasPermission('visit-report.create-any')) {
            return true;
        }

        return $visit->inspector_id === $user->id;
    }

    public function update(User $user, VisitReport $report, Visit $visit): bool
    {
        if ($user->hasPermission('visit-report.update-any')) {
            return true;
        }

        return $visit->inspector_id === $user->id;
    }

    public function destroy(User $user, Visit $visit): bool
    {
        if ($user->hasPermission('visit-report.destroy-any')) {
            return true;
        }

        return $visit->inspector_id === $user->id;
    }
}
