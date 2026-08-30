<?php

namespace App\Services\Front;

use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterService
{
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'type' => 'user',
                'fname' => $data['fname'],
                'lname' => $data['lname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['mobile'],
                'category_id' => $data['category_id'],
                'sub_category_id' => is_array($data['sub_category_id'] ?? null)
                    ? implode(',', array_unique(array_map('strval', $data['sub_category_id'])))
                    : ($data['sub_category_id'] ?? null),
                'status' => 1,
                'email_verified_at' => null,
                'referral_code' => $data['referral_code'] ?? $this->uniqueReferralCode(),
            ]);

            CompanyProfile::create([
                'user_id' => $user->id,
                'company_name' => $data['company_name'],
                'slug' => CompanyProfile::uniqueSlug(
                    Str::slug($data['company_name']) ?: $data['company_slug']
                ),
                'owner_name' => trim("{$data['fname']} {$data['lname']}"),
                'phone' => $data['mobile'],
                'email' => $data['email'],
            ]);

            $this->createUserUploadFolders($data['email']);

            return $user;
        });
    }

    private function createUserUploadFolders(string $email): void
    {
        $basePath = public_path('uploads/'.$email);

        $subFolders = [
            'company-logos',
            'company-documents',
            'documents',
            'services',
            'team-members',
            'projects',
            'articles',
            'offers',
            'videos',
            'advertisements',
        ];

        if (! File::isDirectory($basePath)) {
            File::makeDirectory($basePath, 0777, true);
        }

        foreach ($subFolders as $folder) {
            $folderPath = $basePath.'/'.$folder;
            if (! File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true);
            }
        }
    }

    private function uniqueReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }
}
