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
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('throttle:10,1');

    // Send OTP to email
    Route::get('/auth/send-otp', [AuthController::class, 'showLogin'])->name('auth.show-send-otp')->middleware('throttle:10,1');
    Route::post('/auth/send-otp', [AuthController::class, 'sendOtp'])->name('auth.send-otp')->middleware('throttle:10,1');

    // Verify OTP page
    Route::get('/auth/verify-otp', [AuthController::class, 'showVerify'])->name('auth.show-verify-otp')->middleware('throttle:10,1');
    Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp')->middleware('throttle:10,1');

    // Resend OTP
    Route::get('/auth/resend-otp', [AuthController::class, 'showVerify'])->name('auth.show-resend-otp')->middleware('throttle:10,1');
    Route::post('/auth/resend-otp', [AuthController::class, 'resendOtp'])->name('auth.resend-otp')->middleware('throttle:10,1');
});

Route::get('/', function () {
    return Inertia::render('Landing', [
        'senderEmail' => session('authenticated_email'),
    ]);
})->name('landing')->middleware('throttle:10,1');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('throttle:10,1', AuthenticateWithOtp::class);

// Love 2FA Routes
Route::prefix('love-2fa')->group(function () {
    // Create form
    Route::get('/', [Love2FAController::class, 'create'])->name('love2fa.create')->middleware('throttle:10,1', AuthenticateWithOtp::class);

    // Store new Love2FA
    Route::post('/', [Love2FAController::class, 'store'])->name('love2fa.store')->middleware('throttle:10,1', AuthenticateWithOtp::class);

    // Show Love2FA (auto-detects unlock/guess/revealed state)
    Route::get('/{love2fa:slug}', [Love2FAController::class, 'show'])->name('love2fa.show')->middleware('throttle:10,1');

    // Unlock with PIN
    Route::post('/{love2fa:slug}/unlock', [Love2FAController::class, 'unlock'])->name('love2fa.unlock')->middleware('throttle:10,1');

    // Submit name guess
    Route::post('/{love2fa:slug}/guess', [Love2FAController::class, 'guess'])->name('love2fa.guess')->middleware('throttle:10,1');

    // View hints
    Route::post('/{love2fa:slug}/view-hints', [Love2FAController::class, 'viewHints'])->name('love2fa.view-hints')->middleware('throttle:10,1');
});

Route::prefix('gift')->group(function () {
    Route::get('/{slug}', [Love2FAController::class, 'show'])->name('love2fa.gift.show')->middleware('throttle:10,1');
    Route::post('/{slug}/verify', [Love2FAController::class, 'verify'])->name('love2fa.verify')->middleware('throttle:10,1');
});

// Be My Valentine Routes
Route::prefix('be-my-valentine')->group(function () {
    Route::get('/', [BeMyValentineController::class, 'create'])
        ->name('valentine.create')->middleware('throttle:10,1');

    Route::post('/', [BeMyValentineController::class, 'store'])
        ->name('valentine.store')->middleware('throttle:10,1');
})->middleware(AuthenticateWithOtp::class);

Route::prefix('valentine')->group(function () {
    Route::get('/{valentine:slug}', [BeMyValentineController::class, 'show'])
        ->name('valentine.show')->middleware('throttle:10,1');

    Route::post('/{valentine:slug}/unlock', [BeMyValentineController::class, 'unlock'])
        ->name('valentine.unlock')->middleware('throttle:10,1');

    Route::post('/{valentine:slug}/respond', [BeMyValentineController::class, 'respond'])
        ->name('valentine.respond')->middleware('throttle:10,1');
});

Route::get('BosF9wLNj2mcvKItKeGq70NmCP2L44', function () {
    return Inertia::render('Dashboard')->middleware('throttle:10,1');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
