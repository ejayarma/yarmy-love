<?php

namespace App\Mail;

use App\Models\Love2FA;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Love2FACreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Love2FA $love2fa
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Love 2FA Mystery Gift is Ready!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.love2fa-created',
            with: [
                'senderName' => $this->love2fa->sender_name,
                'recipientName' => $this->love2fa->recipient_name,
                'recipientPincode' => $this->love2fa->recipient_pincode,
                'giftDescription' => $this->love2fa->gift_description,
                'message' => $this->love2fa->message,
                'maxAttempts' => $this->love2fa->max_attempts,
                'hints' => $this->love2fa->hints ?? [],
                'love2faUrl' => route('love2fa.show', $this->love2fa->slug),
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
