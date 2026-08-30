<?php

namespace App\Services\Front;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Support\Collection;

class InquiryService
{
    public function listForUser(User $user): Collection
    {
        return $user->inquiries()
            ->latest()
            ->get();
    }

    public function statsForUser(User $user): array
    {
        $inquiries = $user->inquiries()->get(['status']);

        return [
            'total' => $inquiries->count(),
            'new' => $inquiries->where('status', Inquiry::STATUS_NEW)->count(),
            'replied' => $inquiries->where('status', Inquiry::STATUS_REPLIED)->count(),
        ];
    }

    public function belongsToUser(Inquiry $inquiry, User $user): bool
    {
        return (int) $inquiry->user_id === (int) $user->id;
    }

    public function reply(Inquiry $inquiry, string $reply): Inquiry
    {
        $inquiry->update([
            'reply' => $reply,
            'status' => Inquiry::STATUS_REPLIED,
            'replied_at' => now(),
        ]);

        return $inquiry->fresh();
    }

    public function updateStatus(Inquiry $inquiry, string $status): Inquiry
    {
        $payload = ['status' => $status];

        if ($status === Inquiry::STATUS_NEW) {
            $payload['reply'] = null;
            $payload['replied_at'] = null;
        }

        $inquiry->update($payload);

        return $inquiry->fresh();
    }
}
