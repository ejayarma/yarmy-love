<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeMyValentineController;
use App\Http\Controllers\Love2FAController;
use App\Http\Middleware\AuthenticateWithOtp;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login page (email entry)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('5rottle:10,1');

    // Send OTP to email
    Route::post('/auth/send-otp', [AuthController::class, 'sendOtp'])->name('auth.send-otp')->middleware('throttle:5,1');

    // Verify OTP page
    Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp')->middleware('throttle:5,1');

    // Resend OTP
    Route::post('/auth/resend-otp', [AuthController::class, 'resendOtp'])->name('auth.resend-otp')->middleware('throttle:5,1');
});

Route::middleware(AuthenticateWithOtp::class)->group(function () {
    Route::get('/', function () {
        return Inertia::render('Landing', [
            'senderEmail' => session('authenticated_email'),
        ]);
    })->name('landing')->middleware('throttle:5,1');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('throttle:5,1');

    // Love 2FA Routes
    Route::prefix('love-2fa')->group(function () {
        // Create form
        Route::get('/', [Love2FAController::class, 'create'])->name('love2fa.create')->middleware('throttle:5,1');

        // Store new Love2FA
        Route::post('/', [Love2FAController::class, 'store'])->name('love2fa.store')->middleware('throttle:5,1');

        // Show Love2FA (auto-detects unlock/guess/revealed state)
        Route::get('/{love2fa:slug}', [Love2FAController::class, 'show'])->name('love2fa.show')->middleware('throttle:5,1');

        // Unlock with PIN
        Route::post('/{love2fa:slug}/unlock', [Love2FAController::class, 'unlock'])->name('love2fa.unlock')->middleware('throttle:5,1');

        // Submit name guess
        Route::post('/{love2fa:slug}/guess', [Love2FAController::class, 'guess'])->name('love2fa.guess')->middleware('throttle:5,1');

        // View hints
        Route::post('/{love2fa:slug}/view-hints', [Love2FAController::class, 'viewHints'])->name('love2fa.view-hints')->middleware('throttle:5,1');
    });

    Route::prefix('gift')->group(function () {
        Route::get('/{slug}', [Love2FAController::class, 'show'])->name('love2fa.show')->middleware('throttle:5,1');
        Route::post('/{slug}/verify', [Love2FAController::class, 'verify'])->name('love2fa.verify')->middleware('throttle:5,1');
    });

    // Be My Valentine Routes
    Route::prefix('be-my-valentine')->group(function () {
        Route::get('/', [BeMyValentineController::class, 'create'])
            ->name('valentine.create')->middleware('throttle:5,1');

        Route::post('/', [BeMyValentineController::class, 'store'])
            ->name('valentine.store')->middleware('throttle:5,1');
    });

    Route::prefix('valentine')->group(function () {
        Route::get('/{valentine:slug}', [BeMyValentineController::class, 'show'])
            ->name('valentine.show')->middleware('throttle:5,1');

        Route::post('/{valentine:slug}/unlock', [BeMyValentineController::class, 'unlock'])
            ->name('valentine.unlock')->middleware('throttle:5,1');

        Route::post('/{valentine:slug}/respond', [BeMyValentineController::class, 'respond'])
            ->name('valentine.respond')->middleware('throttle:5,1');
    });

});
