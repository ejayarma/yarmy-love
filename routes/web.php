<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeMyValentineController;
use App\Http\Controllers\Love2FAController;
use App\Http\Middleware\AuthenticateWithOtp;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login page (email entry)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

    // Send OTP to email
    Route::post('/auth/send-otp', [AuthController::class, 'sendOtp'])->name('auth.send-otp');

    // Verify OTP page
    Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp');

    // Resend OTP
    Route::post('/auth/resend-otp', [AuthController::class, 'resendOtp'])->name('auth.resend-otp');
});

Route::middleware(AuthenticateWithOtp::class)->group(function () {
    Route::get('/', function () {
        return Inertia::render('Landing');
    })->name('landing');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Love 2FA Routes
    Route::prefix('love-2fa')->group(function () {
        // Create form
        Route::get('/', [Love2FAController::class, 'create'])->name('love2fa.create');

        // Store new Love2FA
        Route::post('/', [Love2FAController::class, 'store'])->name('love2fa.store');

        // Show Love2FA (auto-detects unlock/guess/revealed state)
        Route::get('/{love2fa:slug}', [Love2FAController::class, 'show'])->name('love2fa.show');

        // Unlock with PIN
        Route::post('/{love2fa:slug}/unlock', [Love2FAController::class, 'unlock'])->name('love2fa.unlock');

        // Submit name guess
        Route::post('/{love2fa:slug}/guess', [Love2FAController::class, 'guess'])->name('love2fa.guess');

        // View hints
        Route::post('/{love2fa:slug}/view-hints', [Love2FAController::class, 'viewHints'])->name('love2fa.view-hints');
    });

    Route::prefix('gift')->group(function () {
        Route::get('/{slug}', [Love2FAController::class, 'show'])->name('love2fa.show');
        Route::post('/{slug}/verify', [Love2FAController::class, 'verify'])->name('love2fa.verify');
    });

    // Be My Valentine Routes
    Route::prefix('be-my-valentine')->group(function () {
        Route::get('/', [BeMyValentineController::class, 'create'])
            ->name('valentine.create');

        Route::post('/', [BeMyValentineController::class, 'store'])
            ->name('valentine.store');
    });

    Route::prefix('valentine')->group(function () {
        Route::get('/{valentine:slug}', [BeMyValentineController::class, 'show'])
            ->name('valentine.show');

        Route::post('/{valentine:slug}/unlock', [BeMyValentineController::class, 'unlock'])
            ->name('valentine.unlock');

        Route::post('/{valentine:slug}/respond', [BeMyValentineController::class, 'respond'])
            ->name('valentine.respond');
    });

});

// Route::get('velcro-magnanimity', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard-old');

// require __DIR__.'/settings.php';
