<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Visit;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VisitAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Visit $visit
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Allarme Visita Tecnica - ' . $this->visit->supplier->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.inspector.alert',
            with: [
                'visit' => $this->visit,
                'supplier' => $this->visit->supplier,
                'criticalIssue' => $this->visit->critical_issue,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
