<?php

use App\Http\Controllers\BeMyValentineController;
use App\Http\Controllers\Love2FAController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Landing');
})->name('landing');

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
    Route::get('/{token}', [Love2FAController::class, 'show'])->name('love2fa.show');
    Route::post('/{token}/verify', [Love2FAController::class, 'verify'])->name('love2fa.verify');
});

// Be My Valentine Routes
Route::prefix('be-my-valentine')->group(function () {
    Route::get('/', [BeMyValentineController::class, 'create'])->name('valentine.create');
    Route::post('/', [BeMyValentineController::class, 'store'])->name('valentine.store');
});

Route::prefix('valentine')->group(function () {
    Route::get('/{token}', [BeMyValentineController::class, 'show'])->name('valentine.show');
    Route::post('/{token}/verify', [BeMyValentineController::class, 'verify'])->name('valentine.verify');
    Route::post('/{token}/respond', [BeMyValentineController::class, 'respond'])->name('valentine.respond');
});

Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('velcro-magnanimity', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
