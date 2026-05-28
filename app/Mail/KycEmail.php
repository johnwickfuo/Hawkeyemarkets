<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KycEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $kyc_record;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $kyc_record)
    {
        $this->subject = $subject;
        $this->kyc_record = $kyc_record;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Dynamically resolve view based on active template
        $template = config('site.template');
        $view = "templates.{$template}.mail.kyc-email";

        return new Content(
            markdown: $view,
            with: [
                'kyc_record' => $this->kyc_record,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
