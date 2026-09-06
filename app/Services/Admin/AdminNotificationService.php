<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Collection;

class AdminNotificationService
{
    public function sendToAll(string $title, ?string $body, string $type = 'general'): int
    {
        $count = 0;
        $now = now();

        User::query()
            ->whereIn('type', ['user', 'agent'])
            ->orderBy('id')
            ->chunkById(200, function ($users) use (&$count, $title, $body, $type, $now) {
                $rows = [];

                foreach ($users as $user) {
                    $rows[] = [
                        'user_id' => $user->id,
                        'title' => $title,
                        'body' => $body,
                        'type' => $type,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if ($rows) {
                    UserNotification::insert($rows);
                    $count += count($rows);
                }
            });

        return $count;
    }

    public function sendToUser(User $user, string $title, ?string $body, string $type = 'general'): UserNotification
    {
        return UserNotification::create([
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
            'type' => $type,
        ]);
    }

    public function searchUsers(string $query): Collection
    {
        $term = trim($query);

        return User::query()
            ->whereIn('type', ['user', 'agent'])
            ->when($term !== '', function ($builder) use ($term) {
                $builder->where(function ($inner) use ($term) {
                    $inner->where('fname', 'like', "%{$term}%")
                        ->orWhere('lname', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%");
                });
            })
            ->orderBy('fname')
            ->limit(20)
            ->get(['id', 'fname', 'lname', 'email']);
    }

    public function recent(int $limit = 12)
    {
        return UserNotification::query()
            ->with('user:id,fname,lname,email')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
