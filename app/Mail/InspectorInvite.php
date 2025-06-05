<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Visit;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InspectorInvite extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Visit $visit
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invito per Ispezione Tecnica - ' . $this->visit->supplier->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.inspector.invite',
            with: [
                'visit' => $this->visit,
                'supplier' => $this->visit->supplier,
                'visitDate' => $this->visit->date->format('d/m/Y'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
