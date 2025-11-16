<?php

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Auth\PasswordResetLinkController;
// use App\Http\Controllers\Auth\NewPasswordController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::post('/login', function (Request $r) {
//     $r->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//     ]);

//     if (!Auth::attempt($r->only('email', 'password'), $r->boolean('remember'))) {
//         // generic error to avoid information leak
//         return response()->json(['message' => 'Invalid credentials'], 422);
//     }

//     $r->session()->regenerate(); // prevent session fixation

//     return response()->json(['message' => 'ok']);
// })->middleware('throttle:login'); // keep your login rate limit

// Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
//     ->name('password.email');

// Route::get('/reset-password/{token}', function ($token) {
//     $email = request('email');
//     return redirect("http://localhost:5173/reset-password?token=$token&email=$email");
// })->name('password.reset');

// Route::post('/reset-password', [NewPasswordController::class, 'store'])
//     ->name('password.update');

