<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\Enums\VisitStatus;
use App\Models\Visit;
use Illuminate\Validation\ValidationException;

class UpdateVisitStatus
{
    public function execute(Visit $visit, string $status): Visit
    {
        // Validate status
        if (! in_array($status, VisitStatus::toValuesArray())) {
            throw ValidationException::withMessages([
                'status' => 'Invalid visit status provided.',
            ]);
        }

        $visit->update(['status' => $status]);

        return $visit->fresh();
    }
}
