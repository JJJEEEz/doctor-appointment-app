<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
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
        $testRecipient = env('MAIL_TEST_TO_ADDRESS');

        if (filled($testRecipient) && app()->environment(['local', 'testing'])) {
            Mail::alwaysTo($testRecipient);
        }
    }
}
