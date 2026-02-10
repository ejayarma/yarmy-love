<?php

namespace App\Http\Controllers;

use App\Models\Love2FA;
use App\Models\Love2FAAttempt;
use App\Mail\Love2FAAttemptMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class Love2FAAttemptController extends Controller
{
    /**
     * Submit a guess attempt for a Love2FA mystery.
     */
    public function store(Request $request, Love2FA $love2fa)
    {
        // Validate the request
        $request->validate([
            'code' => ['required', 'string', 'size:4'],
        ]);

        // Check if already solved
        if ($love2fa->isSolved()) {
            return back()->with('error', 'This mystery has already been solved!');
        }

        // Check if attempts remaining
        if (!$love2fa->hasAttemptsRemaining()) {
            return back()->with('error', 'No attempts remaining. The mystery stays unsolved!');
        }

        // Record the attempt
        $attempt = $love2fa->recordAttempt(
            $request->input('code'),
            $request->ip(),
            $request->userAgent()
        );

        // Send email notification to sender
        Mail::to($love2fa->sender_email)->send(
            new Love2FAAttemptMail($love2fa, $attempt)
        );

        // Return response based on result
        if ($attempt->is_correct) {
            return redirect()
                ->route('love2fa.revealed', $love2fa)
                ->with('success', '🎉 Correct! You\'ve solved the mystery!');
        }

        $attemptsRemaining = $love2fa->remaining_attempts;

        if ($attemptsRemaining === 0) {
            return back()->with('error', 'Incorrect! No attempts remaining. The mystery stays unsolved!');
        }

        return back()->with('error', "Incorrect! You have {$attemptsRemaining} attempt(s) remaining.");
    }

    /**
     * Show all attempts for a Love2FA (for sender only).
     */
    public function index(Request $request, Love2FA $love2fa)
    {
        // In production, add authentication/authorization check here
        // to ensure only the sender can view attempts

        $attempts = $love2fa->attempts()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($attempt) {
                return [
                    'id' => $attempt->id,
                    'code' => $attempt->guessed_code,
                    'is_correct' => $attempt->is_correct,
                    'status' => $attempt->status,
                    'status_emoji' => $attempt->status_emoji,
                    'formatted_time' => $attempt->formatted_time,
                    'ip_address' => $attempt->ip_address,
                    'created_at' => $attempt->created_at,
                ];
            });

        return Inertia::render('Love2FA/Attempts', [
            'love2fa' => [
                'id' => $love2fa->id,
                'recipient_name' => $love2fa->recipient_name,
                'max_attempts' => $love2fa->max_attempts,
                'remaining_attempts' => $love2fa->remaining_attempts,
                'total_attempts' => $love2fa->total_attempts,
                'is_revealed' => $love2fa->is_revealed,
            ],
            'attempts' => $attempts,
        ]);
    }

    /**
     * Get attempt statistics for a Love2FA.
     */
    public function statistics(Love2FA $love2fa)
    {
        return response()->json([
            'total_attempts' => $love2fa->total_attempts,
            'correct_attempts' => $love2fa->correct_attempts,
            'incorrect_attempts' => $love2fa->incorrect_attempts,
            'remaining_attempts' => $love2fa->remaining_attempts,
            'is_solved' => $love2fa->isSolved(),
            'hints_viewed' => $love2fa->hints_viewed,
            'last_attempt' => $love2fa->last_attempt ? [
                'code' => $love2fa->last_attempt->guessed_code,
                'is_correct' => $love2fa->last_attempt->is_correct,
                'time' => $love2fa->last_attempt->formatted_time,
            ] : null,
        ]);
    }
}

