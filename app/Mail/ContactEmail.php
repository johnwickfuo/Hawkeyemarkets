<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $sender_name;
    public $sender_email;
    public $sender_message;
    public $title;

    /**
     * Create a new message instance.
     */
    public function __construct($sender_name, $sender_email, $sender_message, $title)
    {
        $this->sender_name = $sender_name;
        $this->sender_email = $sender_email;
        $this->sender_message = $sender_message;
        $this->title = $title;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Contact Email Submission: " . $this->title,
            replyTo: [
                new \Illuminate\Mail\Mailables\Address($this->sender_email, $this->sender_name),
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Dynamically resolve view based on active template
        $template = config('site.template');
        $view = "templates.{$template}.mail.contact-email";

        return new Content(
            markdown: $view,
            with: [
                'sender_name' => $this->sender_name,
                'sender_email' => $this->sender_email,
                'sender_message' => $this->sender_message,
                'title' => $this->title,
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
