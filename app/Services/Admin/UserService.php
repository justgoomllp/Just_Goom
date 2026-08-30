<?php

namespace App\Services\Admin;

use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function getAll()
    {
        return User::with(['category', 'subCategory'])
            ->latest()
            ->paginate(10);
    }

    public function store(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['status'] = $data['status'] ?? 1;
        $data['profile'] = $this->uploadProfile($data['profile'] ?? null);
        $data['email_verified_at'] = ! empty($data['email_verified']) ? now() : null;

        if (empty($data['referral_code'])) {
            $data['referral_code'] = $this->uniqueReferralCode();
        }

        if (empty($data['email_verified_at'])) {
            $data['email_verified_at'] = now();
        }

        unset($data['email_verified']);

        $user = User::create($data);

        if (in_array($user->type, ['user', 'agent'], true)) {
            $this->ensureCompanyProfile($user);
        }

        return $user;
    }

    public function update(User $user, array $data): User
    {
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['status'] = $data['status'] ?? 1;
        $data['email_verified_at'] = ! empty($data['email_verified']) ? ($user->email_verified_at ?? now()) : null;

        if (isset($data['profile']) && $data['profile'] instanceof UploadedFile) {
            $this->deleteProfile($user->profile);
            $data['profile'] = $this->uploadProfile($data['profile']);
        } else {
            unset($data['profile']);
        }

        if (empty($data['referral_code'])) {
            $data['referral_code'] = $user->referral_code ?: $this->uniqueReferralCode();
        }

        unset($data['email_verified']);

        $user->update($data);

        if (in_array($user->type, ['user', 'agent'], true)) {
            $this->ensureCompanyProfile($user->fresh());
        }

        return $user;
    }

    public function delete(User $user): void
    {
        $this->deleteProfile($user->profile);
        $user->delete();
    }

    private function uniqueReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }

    private function uploadProfile($file): ?string
    {
        if (! $file instanceof UploadedFile) {
            return null;
        }

        $destination = public_path('uploads/user-profiles');
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = time().'-'.Str::random(12).'.'.$file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/user-profiles/'.$filename;
    }

    private function deleteProfile(?string $profile): void
    {
        if (! $profile) {
            return;
        }

        $path = public_path($profile);
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    public function ensureCompanyProfile(User $user): CompanyProfile
    {
        $existing = $user->companyProfile;

        if ($existing) {
            return $existing;
        }

        $companyName = trim("{$user->fname} {$user->lname}") ?: 'Company '.$user->id;

        return CompanyProfile::create([
            'user_id' => $user->id,
            'company_name' => $companyName,
            'slug' => $this->uniqueCompanySlug($companyName),
            'owner_name' => $companyName,
            'phone' => $user->phone,
            'email' => $user->email,
            'city' => $user->city,
            'state' => $user->state,
            'country' => $user->country,
        ]);
    }

    public function syncMissingCompanyProfiles(): int
    {
        $count = 0;

        User::query()
            ->whereIn('type', ['user', 'agent'])
            ->where('status', 1)
            ->whereDoesntHave('companyProfile')
            ->each(function (User $user) use (&$count) {
                $this->ensureCompanyProfile($user);
                $count++;
            });

        return $count;
    }

    private function uniqueCompanySlug(string $name): string
    {
        $base = Str::slug($name) ?: 'company';
        $slug = $base;
        $suffix = 1;

        while (CompanyProfile::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
