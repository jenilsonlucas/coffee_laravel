<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void {
        RateLimiter::for('applaunch', function(Request $request) {
            return Limit::perMinute(5)
                ->by(Auth::id() ?: $request->ip());
        });

        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
            request()->server->set('HTTPS', 'on');
        }

        VerifyEmail::toMailUsing(function (object $notifiable, string $url){
            return (new MailMessage)
            ->view("email.confirm", [
                "confirm_link" => $url
            ]);
        });
    }
}
