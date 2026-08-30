<?php

namespace App\Listeners;

use App\Models\LoginHistory;
use Illuminate\Auth\Events\Logout;

class RecordLogoutHistory
{
    public function handle(Logout $event): void
    {
        if ($event->user && !$event->user->isAdmin()) {
            $lastLogin = LoginHistory::where('user_id', $event->user->id)
                ->whereNull('logout_at')
                ->orderByDesc('login_at')
                ->first();

            if ($lastLogin) {
                $lastLogin->update(['logout_at' => now()]);
            }
        }
    }
}
