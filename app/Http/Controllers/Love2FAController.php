<?php

namespace App\Http\Controllers;

use App\Mail\Love2FAAttemptMail;
use App\Mail\Love2FACreatedMail;
use App\Models\Love2FA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class Love2FAController extends Controller
{
    /**
     * Show the Love 2FA creation form
     */
    public function create()
    {
        return Inertia::render('Love2FA/Create', [
            'senderEmail' => Session::get('authenticated_email'),
        ]);
    }

    /**
     * Store a new Love 2FA link
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'required|email',
            'recipient_name' => 'required|string|max:255',
            'recipient_pincode' => 'required|digits:4',
            'gift_description' => 'required|string|max:500',
            'message' => 'required|string|max:1000',
            'max_attempts' => 'required|integer|min:1|max:10',
            'hints' => 'nullable|array|max:5',
            'hints.*' => 'string|max:255',
        ]);

        $validated['sender_email'] = session('authenticated_email');

        $love2fa = Love2FA::create([
            'sender_name' => $validated['sender_name'],
            'sender_email' => $validated['sender_email'],
            'recipient_name' => $validated['recipient_name'],
            'recipient_pincode' => $validated['recipient_pincode'],
            'gift_description' => $validated['gift_description'],
            'message' => $validated['message'],
            'max_attempts' => $validated['max_attempts'],
            'hints' => $validated['hints'] ?? [],
        ]);

        $shareableLink = route('love2fa.show', $love2fa->slug);

        Log::info("New Love2FA created with slug: {$love2fa->slug} by {$validated['sender_email']}");

        // Send email to sender
        defer(function () use ($love2fa) {
            try {
                Mail::to($love2fa->sender_email)->send(
                    new Love2FACreatedMail($love2fa)
                );
            } catch (\Exception $e) {
                Log::error('Failed to send Love2FA creation email: '.$e->getMessage());
            }
        });

        return Inertia::render('Love2FA/Create', [
            'generatedLink' => $shareableLink,
            'recipientPincode' => $validated['recipient_pincode'],
            'senderEmail' => Session::get('authenticated_email'),
        ]);
    }

    /**
     * Show the Love2FA page (checks unlock status)
     */
    public function show(Love2FA $love2fa)
    {
        // If not unlocked yet, show PIN entry screen
        if (! $love2fa->is_unlocked && ! session()->has("love2fa_{$love2fa->id}_unlocked")) {
            return Inertia::render('Love2FA/Unlock', [
                'love2fa' => $love2fa->toPublicArray(),
            ]);
        }

        // If already solved, show revealed page
        if ($love2fa->isSolved()) {
            return Inertia::render('Love2FA/Revealed', [
                'love2fa' => $love2fa->toRevealedArray(),
                'attempts' => $love2fa->attempts()
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(fn ($attempt) => [
                        'id' => $attempt->id,
                        'guessed_name' => $attempt->guessed_name,
                        'is_correct' => $attempt->is_correct,
                        'formatted_time' => $attempt->formatted_time,
                    ]),
            ]);
        }

        // Show the guessing game
        return Inertia::render('Love2FA/Guess', [
            'love2fa' => $love2fa->toUnlockedArray(),
            'attempts' => $love2fa->attempts()
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn ($attempt) => [
                    'id' => $attempt->id,
                    'guessed_name' => $attempt->masked_name,
                    'is_correct' => $attempt->is_correct,
                    'formatted_time' => $attempt->formatted_time,
                ]),
        ]);
    }

    /**
     * Unlock the Love2FA with PIN code
     */
    public function unlock(Request $request, Love2FA $love2fa)
    {
        $request->validate([
            'pincode' => ['required', 'string', 'size:4'],
        ]);

        if (! $love2fa->verifyPincode($request->input('pincode'))) {
            return back()->with('error', 'Incorrect PIN code. Please try again.');
        }

        // Mark as unlocked in database
        $love2fa->unlock();

        // Store in session to remember they unlocked it
        session()->put("love2fa_{$love2fa->id}_unlocked", true);

        return redirect()->route('love2fa.show', $love2fa)
            ->with('success', '🎉 Mystery unlocked! Now guess who sent it!');
    }

    /**
     * Submit a name guess attempt
     */
    public function guess(Request $request, Love2FA $love2fa)
    {
        // Validate the request
        $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
        ]);

        // Check if unlocked
        if (! $love2fa->is_unlocked && ! session()->has("love2fa_{$love2fa->id}_unlocked")) {
            return redirect()->route('love2fa.show', $love2fa)
                ->with('error', 'Please enter the PIN code first!');
        }

        // Check if already solved
        if ($love2fa->isSolved()) {
            return redirect()->route('love2fa.show', $love2fa)
                ->with('error', 'This mystery has already been solved!');
        }

        // Check if attempts remaining
        if (! $love2fa->hasAttemptsRemaining()) {
            return back()->with('error', 'No attempts remaining. The mystery stays unsolved! 😢');
        }

        // Record the attempt
        $attempt = $love2fa->recordAttempt(
            $request->input('name'),
            $request->ip(),
            $request->userAgent()
        );

        // Send email notification to sender
        defer(function () use ($love2fa, $attempt) {
            try {
                Mail::to($love2fa->sender_email)->send(
                    new Love2FAAttemptMail($love2fa, $attempt)
                );
            } catch (\Exception $e) {
                Log::error('Failed to send Love2FA attempt email: '.$e->getMessage());
            }
        });

        // Return response based on result
        if ($attempt->is_correct) {
            return redirect()
                ->route('love2fa.show', $love2fa)
                ->with('success', "🎉 Correct! It was {$love2fa->sender_name}!");
        }

        $attemptsRemaining = $love2fa->remaining_attempts;

        if ($attemptsRemaining === 0) {
            return back()->with('error', '❌ Incorrect! No attempts remaining. The mystery stays unsolved!');
        }

        return back()->with('error', "❌ Incorrect! You have {$attemptsRemaining} attempt(s) remaining. Try again!");
    }

    /**
     * View hints (increments hints_viewed counter)
     */
    public function viewHints(Love2FA $love2fa)
    {
        // Check if unlocked
        if (! $love2fa->is_unlocked && ! session()->has("love2fa_{$love2fa->id}_unlocked")) {
            return redirect()->route('love2fa.show', $love2fa);
        }

        $love2fa->incrementHintsViewed();

        return back()->with('success', '💡 Hints revealed! Use them wisely!');
    }
}
