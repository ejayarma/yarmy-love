<?php

namespace App\Http\Controllers;

use App\Mail\ValentineCreatedMail;
use App\Mail\ValentineResponseMail;
use App\Models\Valentine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class BeMyValentineController extends Controller
{
    /**
     * Show the Be My Valentine creation form
     */
    public function create()
    {
        return Inertia::render('BeMyValentine/Create', [
            'senderEmail' => session('authenticated_email'),
        ]);
    }

    /**
     * Store a new Valentine request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'required|email',
            'crush_name' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'force_yes' => 'nullable|boolean',
            'use_pincode' => 'nullable|boolean',
            'pincode' => 'required_if:use_pincode,true|nullable|digits:4',
        ]);

        $validated['sender_email'] = session('authenticated_email');

        $valentine = Valentine::create([
            'sender_name' => $validated['sender_name'],
            'sender_email' => $validated['sender_email'],
            'crush_name' => $validated['crush_name'],
            'message' => $validated['message'],
            'force_yes' => $validated['force_yes'] ?? false,
            'pincode' => $validated['use_pincode'] && $validated['pincode']
                ? $validated['pincode']
                : null,
        ]);

        $shareableLink = route('valentine.show', $valentine->slug);

        Log::info("New Valentine request created with slug: {$valentine->slug} by {$validated['sender_email']}");

        // Send email to sender
        defer(function () use ($valentine) {
            try {
                Mail::to($valentine->sender_email)->send(
                    new ValentineCreatedMail($valentine)
                );
            } catch (\Exception $e) {
                Log::error('Failed to send Valentine creation email: '.$e->getMessage());
            }
        });

        return Inertia::render('BeMyValentine/Create', [
            'generatedLink' => $shareableLink,
            'pincode' => $validated['use_pincode'] ? $validated['pincode'] : null,
            'senderEmail' => session('authenticated_email'),
        ]);
    }

    /**
     * Show the valentine request to the recipient
     */
    public function show(Valentine $valentine)
    {
        // If already responded
        if ($valentine->response) {
            return Inertia::render('BeMyValentine/AlreadyResponded', [
                'valentine' => [
                    'crush_name' => $valentine->crush_name,
                    'response' => $valentine->response,
                    'responded_at' => $valentine->responded_at,
                ],
            ]);
        }

        // If PIN required and not unlocked in session
        if ($valentine->pincode && ! session()->has("valentine_{$valentine->id}_unlocked")) {
            return Inertia::render('BeMyValentine/Unlock', [
                'valentine' => [
                    'id' => $valentine->id,
                    'slug' => $valentine->slug,
                    'crush_name' => $valentine->crush_name,
                ],
            ]);
        }

        // Show the actual valentine request
        return Inertia::render('BeMyValentine/View', [
            'valentine' => [
                'id' => $valentine->id,
                'slug' => $valentine->slug,
                'sender_name' => $valentine->sender_name,
                'crush_name' => $valentine->crush_name,
                'message' => $valentine->message,
                'force_yes' => $valentine->force_yes,
            ],
        ]);
    }

    /**
     * Unlock valentine with PIN code
     */
    public function unlock(Request $request, Valentine $valentine)
    {
        $request->validate([
            'pincode' => ['required', 'string', 'size:4'],
        ]);

        if ($valentine->pincode !== $request->input('pincode')) {
            return back()->with('error', 'Incorrect PIN code. Please try again.');
        }

        // Store in session that they've unlocked it
        session()->put("valentine_{$valentine->id}_unlocked", true);

        return redirect()->route('valentine.show', $valentine->slug)
            ->with('success', '💕 Message unlocked!');
    }

    /**
     * Store the recipient's response
     */
    public function respond(Request $request, Valentine $valentine)
    {
        // Check if already responded
        if ($valentine->response) {
            return back()->with('error', 'You have already responded to this request.');
        }

        // Check if unlocked (if PIN required)
        if ($valentine->pincode && ! session()->has("valentine_{$valentine->id}_unlocked")) {
            return redirect()->route('valentine.show', $valentine->slug)
                ->with('error', 'Please enter the PIN code first!');
        }

        $validated = $request->validate([
            'response' => 'required|in:yes,no,maybe',
            'message' => 'nullable|string|max:500',
        ]);

        $valentine->update([
            'response' => $validated['response'],
            'response_message' => $validated['message'] ?? null,
            'responded_at' => now(),
        ]);

        // Send email to sender
        defer(function () use ($valentine, $validated) {
            try {
                Mail::to($valentine->sender_email)->send(
                    new ValentineResponseMail(
                        $valentine,
                        $validated['response'],
                        $validated['message'] ?? null
                    )
                );
            } catch (\Exception $e) {
                Log::error('Failed to send Valentine response email: '.$e->getMessage());
            }
        });

        return Inertia::render('BeMyValentine/ResponseSent', [
            'response' => $validated['response'],
            'sender_name' => $valentine->sender_name,
        ]);
    }
}
