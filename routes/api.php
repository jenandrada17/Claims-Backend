<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', fn() => ['ok' => true, 'time' => now()->toDateTimeString()]);

Route::post('/logout', function (Request $r) {
    Auth::guard('web')->logout();
    $r->session()->invalidate();
    $r->session()->regenerateToken();
    return response()->json(['message' => 'ok']);
});

Route::post('/login', function (Request $r) {
    $r->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($r->only('email', 'password'), $r->boolean('remember'))) {
        // generic error to avoid information leak
        return response()->json(['message' => 'Invalid credentials'], 422);
    }

    $r->session()->regenerate(); // prevent session fixation

    return response()->json(['message' => 'ok']);
})->middleware('throttle:login'); // keep your login rate limit

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    $email = request('email');
    return redirect("http://localhost:5173/reset-password?token=$token&email=$email");
})->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.update');


Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return $request->user();
});

// force redeploy
