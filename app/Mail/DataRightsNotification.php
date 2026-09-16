<?php

namespace App\Mail;

use App\Models\DataRightsRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DataRightsNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly DataRightsRequest $dataRequest) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[GDPR] New Data Rights Request — '.strtoupper($this->dataRequest->request_type).' — '.$this->dataRequest->email,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.data-rights.notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
