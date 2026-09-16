<?php

namespace App\Mail;

use App\Models\DataRightsRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DataRightsConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly DataRightsRequest $dataRequest) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->dataRequest->locale) {
            'fr' => 'Confirmation de votre demande de droit — A.V.C Institute',
            'fa' => 'تأیید درخواست حقوق داده شما — موسسه A.V.C',
            default => 'Confirmation of Your Data Rights Request — A.V.C Institute',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.data-rights.confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
