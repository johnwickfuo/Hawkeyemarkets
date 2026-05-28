<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $holding_history;
    public $custom_subject;
    public $custom_message;

    /**
     * Create a new message instance.
     */
    public function __construct($holding_history, $custom_subject, $custom_message)
    {
        $this->holding_history = $holding_history;
        $this->custom_subject = $custom_subject;
        $this->custom_message = $custom_message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __($this->custom_subject),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Dynamically resolve view based on active template
        $template = config('site.template');
        $view = "templates.{$template}.mail.stock-email";

        return new Content(
            markdown: $view,
            with: [
                'holding_history' => $this->holding_history,
                'custom_subject' => $this->custom_subject,
                'custom_message' => $this->custom_message,
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
