<?php

namespace App\Mail;

use App\Models\Love2FA;
use App\Models\Love2FAAttempt;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Love2FAAttemptMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Love2FA $love2fa,
        public Love2FAAttempt $attempt
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->attempt->is_correct
            ? '🎉 Mystery Solved! They Guessed Your Name!'
            : '🔍 New Guess Attempt on Your Love 2FA';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Get all previous attempts
        $previousAttempts = $this->love2fa->attempts()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($attempt) {
                return [
                    'name' => $attempt->guessed_name,
                    'is_correct' => $attempt->is_correct,
                    'time' => $attempt->created_at->format('M d, Y - h:i A'),
                ];
            })
            ->toArray();

        // Calculate attempts remaining
        $totalAttempts = $this->love2fa->attempts()->count();
        $attemptsRemaining = max(0, $this->love2fa->max_attempts - $totalAttempts);

        return new Content(
            view: 'emails.love2fa-attempt',
            with: [
                'senderName' => $this->love2fa->sender_name,
                'recipientName' => $this->love2fa->recipient_name,
                'guessedName' => $this->attempt->guessed_name,
                'isCorrect' => $this->attempt->is_correct,
                'attemptTime' => $this->attempt->created_at->format('M d, Y - h:i A'),
                'attemptsRemaining' => $attemptsRemaining,
                'totalAttempts' => $totalAttempts,
                'hintsViewed' => $this->love2fa->hints_viewed ?? 0,
                'love2faUrl' => route('love2fa.show', $this->love2fa->slug),
                'previousAttempts' => $previousAttempts,
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
