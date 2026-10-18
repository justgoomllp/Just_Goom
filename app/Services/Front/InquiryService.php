<?php

namespace App\Services\Front;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

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

    public function createForProfile(User $user, array $data): Inquiry
    {
        $duplicate = Inquiry::query()
            ->where('user_id', $user->id)
            ->where('sender_email', $data['sender_email'])
            ->where('subject', $data['subject'])
            ->where('created_at', '>=', now()->subMinutes(2))
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'email' => 'We already received a similar inquiry. Please wait a moment before sending again.',
            ]);
        }

        return Inquiry::create([
            'user_id' => $user->id,
            'sender_name' => $data['sender_name'],
            'sender_company' => $data['sender_company'],
            'sender_email' => $data['sender_email'],
            'sender_phone' => $data['sender_phone'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => Inquiry::STATUS_NEW,
        ]);
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
