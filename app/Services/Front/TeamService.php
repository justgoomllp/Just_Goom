<?php

namespace App\Services\Front;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TeamService
{
    public function listForUser(User $user): Collection
    {
        return $user->teams()
            ->latest()
            ->get();
    }

    public function listForPublicProfile(User $user): Collection
    {
        return $user->teams()
            ->where('status', 1)
            ->latest()
            ->get();
    }

    public function statsForUser(User $user): array
    {
        $members = $user->teams()->get(['status', 'is_primary']);

        return [
            'total' => $members->count(),
            'active' => $members->where('status', 1)->count(),
            'primary' => $members->where('is_primary', true)->count(),
        ];
    }

    public function store(User $user, array $data): Team
    {
        return DB::transaction(function () use ($user, $data) {
            if (! empty($data['is_primary'])) {
                $this->clearPrimaryContact($user);
            }

            return $user->teams()->create([
                'name' => $data['name'],
                'designation' => $data['designation'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'department' => $data['department'] ?? null,
                'status' => (int) $data['status'],
                'is_primary' => ! empty($data['is_primary']),
                'short_info' => $data['short_info'] ?? null,
                'image' => $this->uploadImage($data['image'] ?? null),
            ]);
        });
    }

    public function update(User $user, Team $team, array $data): Team
    {
        return DB::transaction(function () use ($user, $team, $data) {
            if (! empty($data['is_primary'])) {
                $this->clearPrimaryContact($user, $team->id);
            }

            $payload = [
                'name' => $data['name'],
                'designation' => $data['designation'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'department' => $data['department'] ?? null,
                'status' => (int) $data['status'],
                'is_primary' => ! empty($data['is_primary']),
                'short_info' => $data['short_info'] ?? null,
            ];

            if (($data['image'] ?? null) instanceof UploadedFile) {
                $this->deleteImage($team->image);
                $payload['image'] = $this->uploadImage($data['image']);
            }

            $team->update($payload);

            return $team->fresh();
        });
    }

    public function delete(User $user, Team $team): void
    {
        DB::transaction(function () use ($team) {
            $this->deleteImage($team->image);
            $team->delete();
        });
    }

    public function belongsToUser(Team $team, User $user): bool
    {
        return (int) $team->user_id === (int) $user->id;
    }

    public function updateStatus(Team $team, int $status): Team
    {
        $team->update(['status' => $status ? 1 : 0]);

        return $team->fresh();
    }

    private function clearPrimaryContact(User $user, ?int $exceptId = null): void
    {
        $user->teams()
            ->when($exceptId, fn ($query) => $query->where('id', '!=', $exceptId))
            ->where('is_primary', true)
            ->update(['is_primary' => false]);
    }

    private function uploadImage(mixed $file): ?string
    {
        if (! $file instanceof UploadedFile) {
            return null;
        }

        $email = auth()->user()->email;
        $destination = public_path('uploads/' . $email . '/team-members');
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0777, true);
        }

        $filename = time().'-'.Str::random(12).'.'.$file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/' . $email . '/team-members/' . $filename;
    }

    private function deleteImage(?string $image): void
    {
        if (! $image) {
            return;
        }

        $path = public_path($image);
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
