<?php

namespace App\Services\Front;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Collection;

class UserNotificationService
{
    public function listForUser(User $user): Collection
    {
        return $user->userNotifications()
            ->latest()
            ->get();
    }

    public function statsForUser(User $user): array
    {
        $notifications = $user->userNotifications()->get(['read_at']);

        return [
            'total' => $notifications->count(),
            'unread' => $notifications->whereNull('read_at')->count(),
            'read' => $notifications->whereNotNull('read_at')->count(),
        ];
    }

    public function belongsToUser(UserNotification $notification, User $user): bool
    {
        return (int) $notification->user_id === (int) $user->id;
    }

    public function markAsRead(UserNotification $notification): void
    {
        $notification->markAsRead();
    }

    public function markAllAsRead(User $user): int
    {
        return $user->userNotifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function delete(UserNotification $notification): void
    {
        $notification->delete();
    }
}
