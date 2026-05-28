<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RichTextEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $content;
    public $subject;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $content, $subject)
    {
        $this->user = $user;
        $this->content = $content;
        $this->subject = $subject;
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
        $view = "templates.{$template}.mail.rich-text-email";

        return new Content(
            markdown: $view,
            with: [
                'user' => $this->user,
                'content' => $this->content,
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
