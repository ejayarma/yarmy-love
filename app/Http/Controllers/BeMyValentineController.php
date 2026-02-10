<?php

namespace App\Http\Controllers;

use App\Mail\ValentineCreatedMail;
use App\Mail\ValentineResponseMail;
use App\Models\Valentine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BeMyValentineController extends Controller
{
    /**
     * Show the Be My Valentine creation form
     */
    public function create()
    {
        return Inertia::render('BeMyVal');
    }

    /**
     * Store a new Valentine request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_email' => 'required|email',
            'author_name' => 'required|string|max:255',
            'crush_name' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'force_yes' => 'nullable|in:true,false',
            'use_pincode' => 'nullable|in:true,false',
            'pincode' => 'required_if:use_pincode,true|nullable|digits:4',
        ]);

        $token = Str::random(32);

        $valentine = Valentine::create([
            'token' => $token,
            'author_email' => $validated['author_email'],
            'author_name' => $validated['author_name'],
            'crush_name' => $validated['crush_name'],
            'message' => $validated['message'],
            'force_yes' => $validated['force_yes'] ?? false,
            'pincode' => $validated['use_pincode'] && $validated['pincode']
                ? bcrypt($validated['pincode'])
                : null,
        ]);

        $shareableLink = route('valentine.show', ['token' => $token]);

        // Send email to author
        Mail::to($validated['author_email'])->send(
            new ValentineCreatedMail($valentine, $shareableLink)
        );

        return Inertia::render('BeMyValentine/Create', [
            'generatedLink' => $shareableLink,
        ]);
    }

    /**
     * Show the valentine request to the recipient
     */
    // public function show(string $token)
    // {
    //     $valentine = Valentine::where('token', $token)
    //         ->whereNull('response')
    //         ->firstOrFail();

    //     return Inertia::render('BeMyValentine/View', [
    //         'token' => $token,
    //         'requiresPincode' => ! is_null($valentine->pincode),
    //     ]);
    // }

    /**
     * Verify pincode and show valentine request
     */
    // public function verify(Request $request, string $token)
    // {
    //     $valentine = Valentine::where('token', $token)
    //         ->whereNull('response')
    //         ->firstOrFail();

    //     // If no pincode required, just show the message
    //     if (is_null($valentine->pincode)) {
    //         return response()->json([
    //             'success' => true,
    //             'authorName' => $valentine->author_name,
    //             'crushName' => $valentine->crush_name,
    //             'message' => $valentine->message,
    //             'forceYes' => $valentine->force_yes,
    //         ]);
    //     }

    //     // Validate pincode
    //     $request->validate([
    //         'pincode' => 'required|digits:4',
    //     ]);

    //     if (password_verify($request->pincode, $valentine->pincode)) {
    //         return response()->json([
    //             'success' => true,
    //             'authorName' => $valentine->author_name,
    //             'crushName' => $valentine->crush_name,
    //             'message' => $valentine->message,
    //             'forceYes' => $valentine->force_yes,
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'error' => 'Incorrect PIN. Please try again.',
    //     ], 422);
    // }

    /**
     * Store the recipient's response
     */
    // public function respond(Request $request, string $token)
    // {
    //     $valentine = Valentine::where('token', $token)
    //         ->whereNull('response')
    //         ->firstOrFail();

    //     $validated = $request->validate([
    //         'response' => 'required|in:yes,no',
    //     ]);

    //     $valentine->update([
    //         'response' => $validated['response'],
    //         'responded_at' => now(),
    //     ]);

    //     // Send email to author with the response
    //     Mail::to($valentine->author_email)->send(
    //         new ValentineResponseMail($valentine, $validated['response'])
    //     );

    //     return response()->json([
    //         'success' => true,
    //         'response' => $validated['response'],
    //     ]);
    // }

    public function show(Valentine $valentine)
    {
        return Inertia::render('ValentineView', [
            'valentine' => [
                'token' => $valentine->token,
                'requiresPincode' => ! is_null($valentine->pincode),
                'authorName' => $valentine->author_name,
                'crushName' => $valentine->crush_name,
                'message' => $valentine->message,
                'forceYes' => $valentine->force_yes,
            ],
        ]);
    }

    public function verify(Request $request, Valentine $valentine)
    {
        $request->validate([
            'pincode' => 'required|string',
        ]);

        if ($valentine->pincode !== $request->pincode) {
            return back()->withErrors([
                'pincode' => 'Invalid PIN',
            ]);
        }

        return redirect()->route('valentine.show', $valentine->token);
    }

    public function respond(Request $request, Valentine $valentine)
    {
        $request->validate([
            'response' => 'required|in:yes,no',
        ]);

        $valentine->update([
            'response' => $request->response,
            'responded_at' => now(),
        ]);

        Mail::to($valentine->author_email)
            ->send(new ValentineResponseMail(
                $valentine,
                $request->response
            ));

        return redirect()->back()->with('success', true);
    }
}
