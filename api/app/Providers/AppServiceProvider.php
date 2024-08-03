<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
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
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return env("FRONTEND_URL") . "/reset-password/$token";
        });

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = env("FRONTEND_URL") . "/reset-password/$token";

            return (new MailMessage)
                ->subject(Lang::get('Reset Password Notification'))
                ->view('email.auth.reset-password', ['url' => $url]);
        });
    }
}
