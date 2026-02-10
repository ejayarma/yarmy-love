<?php

namespace App\Mail;

use App\Models\Valentine;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ValentineResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Valentine $valentine,
        public string $response,
        public ?string $message = null
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match ($this->response) {
            'yes' => '🎉 They Said YES to Your Valentine Request!',
            'no' => '💌 Response to Your Valentine Request',
            default => '📬 Valentine Response Received',
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.valentine-response',
            with: [
                // 'recipientName' => $this->valentine->recipient_name,
                'recipientName' => $this->valentine->author_name,

                'response' => $this->response,
                'message' => $this->message,
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
