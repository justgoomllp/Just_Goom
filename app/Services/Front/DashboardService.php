<?php

namespace App\Services\Front;

use App\Models\Inquiry;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Collection;

class DashboardService
{
    public function statsForUser(User $user): array
    {
        return [
            'services' => $user->services()->count(),
            'team' => $user->teams()->count(),
            'new_inquiries' => $user->inquiries()->where('status', Inquiry::STATUS_NEW)->count(),
            'notifications' => $user->userNotifications()->whereNull('read_at')->count(),
        ];
    }

    public function recentInquiries(User $user, int $limit = 5): Collection
    {
        return $user->inquiries()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function recentNotifications(User $user, int $limit = 5): Collection
    {
        return $user->userNotifications()
            ->latest()
            ->limit($limit)
            ->get();
    }
}
