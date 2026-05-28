<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountBan extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $action; // 'ban' or 'unban'

    /**
     * Create a new message instance.
     */
    public function __construct($user, $action = 'ban')
    {
        $this->user = $user;
        $this->action = $action;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->action === 'ban'
            ? __('Account Suspended', [], $this->user->lang)
            : __('Account Reactivated', [], $this->user->lang);

        return new Envelope(subject: $subject);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $template = config('site.template');
        $view = "templates.{$template}.mail.account-ban";

        return new Content(
            view: $view,
            with: [
                'user' => $this->user,
                'action' => $this->action,
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
