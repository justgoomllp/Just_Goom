<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * Login/logout listeners are auto-discovered from App\Listeners.
     * Verification email is registered once by Laravel itself.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Laravel already attaches SendEmailVerificationNotification.
     * Registering it here as well would send two verification emails.
     */
    protected function configureEmailVerification(): void
    {
        //
    }
}
