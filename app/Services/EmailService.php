<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EmailLog;
use Exception;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendWithLogging(
        Mailable $mailable,
        string $recipientEmail,
        string $emailType,
        $relatedModel,
        ?string $recipientName = null
    ): EmailLog {
        $emailLog = EmailLog::logEmail(
            $emailType,
            $recipientEmail,
            $relatedModel,
            $recipientName
        );

        try {
            Mail::to($recipientEmail)->send($mailable);
            $emailLog->markAsSent();

            Log::info('Email sent successfully', [
                'type' => $emailType,
                'recipient' => $recipientEmail,
                'related_id' => $relatedModel->id,
            ]);

        } catch (Exception $e) {
            $emailLog->markAsFailed($e->getMessage());

            Log::error('Email sending failed', [
                'type' => $emailType,
                'recipient' => $recipientEmail,
                'error' => $e->getMessage(),
                'related_id' => $relatedModel->id,
            ]);
        }

        return $emailLog;
    }

    public function sendInspectorInvite($visit): EmailLog
    {
        return $this->sendWithLogging(
            new \App\Mail\InspectorInvite($visit),
            $visit->inspector_email,
            'inspector_invite',
            $visit,
            $visit->inspector_name
        );
    }

    public function sendVisitCompleted($visit): EmailLog
    {
        return $this->sendWithLogging(
            new \App\Mail\VisitCompleted($visit),
            $visit->supplier->email,
            'visit_completed',
            $visit,
            $visit->supplier->name
        );
    }

    public function sendVisitAlert($visit): EmailLog
    {
        return $this->sendWithLogging(
            new \App\Mail\VisitAlert($visit),
            $visit->inspector_email,
            'visit_alert',
            $visit,
            $visit->inspector_name
        );
    }
}
