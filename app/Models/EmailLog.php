<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EmailLog extends Model
{
    protected $fillable = [
        'email_type',
        'recipient_email',
        'recipient_name',
        'related_type',
        'related_id',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public static function logEmail(
        string $type,
        string $recipientEmail,
        $relatedModel,
        ?string $recipientName = null,
        string $status = 'pending'
    ): self {
        return self::create([
            'email_type' => $type,
            'recipient_email' => $recipientEmail,
            'recipient_name' => $recipientName,
            'related_type' => get_class($relatedModel),
            'related_id' => $relatedModel->id,
            'status' => $status,
        ]);
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }
}
