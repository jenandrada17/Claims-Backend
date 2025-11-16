<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 5 attempts per minute per (email+IP)
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');
            $ip = (string) $request->ip();

            return [
                Limit::perMinute(5)->by($email . '|' . $ip)
                    ->response(function () {
                        return response()->json([
                            'message' => 'Too many login attempts. Please try again in a minute.'
                        ], 429);
                    }),
            ];
        });
    }
}
