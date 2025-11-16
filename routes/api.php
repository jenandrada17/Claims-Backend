<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return $request->user();
});