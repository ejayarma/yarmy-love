<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Show the login form (email entry)
     */
    public function showLogin()
    {
        if (session('authenticated_email')) {
            return redirect()->route('landing');
        }

        return Inertia::render('LoveAuth/Login');
    }

    /**
     * Show the login form (email entry)
     */
    public function showVerify(Request $request)
    {
        $email = Session::get('otp_email') ?: $request->input('email');

        return Inertia::render('LoveAuth/VerifyOtp', [
            'email' => $email,
        ]);
    }

    /**
     * Send OTP to email
     */
    public function sendOtp(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|email:dns',
            'consent' => 'accepted',
        ], [
            'consent.required' => 'The consent checkbox is required',
            'consent.in' => 'The consent checkbox is required',
        ]);

        try {
            // Generate and send OTP
            Otp::generate($validated['email'], 'login', 10);

            Log::info("OTP sent to: {$validated['email']}");

            // Store email in session temporarily
            Session::put('otp_email', $validated['email']);

            return Inertia::render('LoveAuth/VerifyOtp', [
                'email' => $validated['email'],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP: '.$e->getMessage());

            return back()->with('error', 'Failed to send verification code. Please try again.');
        }
    }

    /**
     * Verify OTP and log in
     */
    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        // Verify OTP
        if (! Otp::verify($validated['email'], $validated['otp'], 'login')) {
            return redirect()->route('auth.show-verify-otp')->with('error', 'Invalid or expired code. Please try again.');
        }

        // Log the user in (store email in session)
        Session::put('authenticated_email', $validated['email']);
        Session::forget('otp_email');

        Log::info("User authenticated: {$validated['email']}");

        return redirect()->intended(route('landing'))
            ->with('success', 'Successfully logged in!');
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $email = Session::get('otp_email') ?? $request->input('email');

        if (! $email) {
            return back()->with('error', 'Session expired. Please start over.');
        }

        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            Otp::generate($email, 'login', 10);

            Log::info("OTP resent to: {$email}");

            return back()->with('success', 'New code sent! Check your email.');
        } catch (\Exception $e) {
            Log::error('Failed to resend OTP: '.$e->getMessage());

            return back()->with('error', 'Failed to send verification code. Please try again.');
        }
    }

    /**
     * Log out
     */
    public function logout(Request $request)
    {
        $email = Session::get('authenticated_email');

        Session::forget('authenticated_email');
        Session::flush();

        Log::info("User logged out: {$email}");

        return redirect()->route('login')
            ->with('success', 'Successfully logged out!');
    }

    /**
     * Show dashboard (or redirect to wherever you want after login)
     */
    public function dashboard()
    {
        $email = Session::get('authenticated_email');

        if (! $email) {
            return redirect()->route('login');
        }

        // Get user's valentines and love2fas
        $valentines = \App\Models\Valentine::where('sender_email', $email)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($valentine) {
                return [
                    'id' => $valentine->id,
                    'slug' => $valentine->slug,
                    'crush_name' => $valentine->crush_name,
                    'response' => $valentine->response,
                    'responded_at' => $valentine->responded_at,
                    'created_at' => $valentine->created_at,
                ];
            });

        $love2fas = \App\Models\Love2FA::where('sender_email', $email)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($love2fa) {
                return [
                    'id' => $love2fa->id,
                    'slug' => $love2fa->slug,
                    'recipient_name' => $love2fa->recipient_name,
                    'is_revealed' => $love2fa->is_revealed,
                    'total_attempts' => $love2fa->total_attempts,
                    'remaining_attempts' => $love2fa->remaining_attempts,
                    'created_at' => $love2fa->created_at,
                ];
            });

        return Inertia::render('Dashboard', [
            'email' => $email,
            'valentines' => $valentines,
            'love2fas' => $love2fas,
        ]);
    }
}
