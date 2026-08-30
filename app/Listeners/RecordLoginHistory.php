<?php

namespace App\Listeners;

use App\Models\LoginHistory;
use Illuminate\Auth\Events\Login;

class RecordLoginHistory
{
    public function handle(Login $event): void
    {
        if ($event->user && !$event->user->isAdmin()) {
            LoginHistory::create([
                'user_id' => $event->user->id,
                'login_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }
}
