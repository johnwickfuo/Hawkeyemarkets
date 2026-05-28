<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReferralEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $referral;
    public $referrer;

    /**
     * Create a new message instance.
     */
    public function __construct($referral, $referrer)
    {
        $this->referral = $referral;
        $this->referrer = $referrer;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('You Have a New Referral'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Dynamically resolve view based on active template
        $template = config('site.template');
        $view = "templates.{$template}.mail.referral-email";

        return new Content(
            markdown: $view,
            with: [
                'referral' => $this->referral,
                'referrer' => $this->referrer,
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
