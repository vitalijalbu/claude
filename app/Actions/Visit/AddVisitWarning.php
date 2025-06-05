<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\Mail\VisitAlert;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Support\Facades\Mail;

class AddVisitWarning
{
    public function execute(Visit $visit, string $criticalIssue, bool $sendAlert = false): Visit
    {
        $visit->update([
            'has_warning' => true,
            'critical_issue' => $criticalIssue,
            'alert_sent' => $sendAlert,
            'alert_sent_at' => $sendAlert ? now() : null,
            'alert_sent_by' => $sendAlert ? $this->resolveRandomUserId() : null,
        ]);

        if ($sendAlert && $visit->inspector_email) {
            Mail::to($visit->inspector_email)->send(new VisitAlert($visit));
        }

        return $visit->fresh();
    }

    private function resolveRandomUserId(): string
    {
        return User::query()
            ->inRandomOrder()
            ->first()
            ->id;
    }
}
