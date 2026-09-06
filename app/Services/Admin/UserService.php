<?php

namespace App\Services\Admin;

use App\Models\CompanyProfile;
use App\Models\User;
use App\Support\AdminDataTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function getAll(?string $search = null)
    {
        return User::with(['category', 'subCategory'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('fname', 'like', "%{$search}%")
                        ->orWhere('lname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function datatable(Request $request): JsonResponse
    {
        $query = User::query()->with('category');

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        if ($request->input('status') !== null && $request->input('status') !== '') {
            $query->where('status', (int) $request->input('status'));
        }

        if ($request->input('email_verified') === '1') {
            $query->whereNotNull('email_verified_at');
        } elseif ($request->input('email_verified') === '0') {
            $query->whereNull('email_verified_at');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->input('category_id'));
        }

        return AdminDataTable::of(
            $request,
            $query,
            [
                'name' => 'fname',
                'email' => 'email',
                'type' => 'type',
                'referral_code' => 'referral_code',
                'status' => 'status',
                'email_verified' => 'email_verified_at',
            ],
            ['fname', 'lname', 'email', 'phone', 'referral_code'],
            function (User $user, int $index) {
                $name = e($user->fullName());
                $avatar = '';
                if ($user->profile) {
                    $avatar = '<img src="'.e(asset($user->profile)).'" alt="'.$name.'" width="36" height="36" class="rounded-circle border me-2" style="object-fit: cover;">';
                }

                $typeMeta = [
                    'admin' => ['Admin', 'badge-info'],
                    'agent' => ['Agent', 'badge-primary'],
                    'user' => ['User', 'badge-secondary'],
                ];
                [$typeLabel, $typeClass] = $typeMeta[$user->type] ?? ['User', 'badge-secondary'];

                return [
                    'DT_RowIndex' => $index,
                    'name' => '<div class="d-flex align-items-center">'.$avatar.$name.'</div>',
                    'email' => e($user->email),
                    'type' => '<label class="badge '.$typeClass.'">'.$typeLabel.'</label>',
                    'referral_code' => e($user->referral_code ?: '-'),
                    'category' => e($user->category->name ?? '-'),
                    'status' => AdminDataTable::statusToggle(route('admin.users.status', $user), (int) $user->status === 1, [
                        'suspended' => (int) $user->status === 2,
                        'disabled' => Auth::id() === $user->id,
                        'disabledTitle' => 'You cannot change your own status',
                    ]),
                    'email_verified' => $user->hasVerifiedEmail()
                        ? '<label class="badge badge-success">Verified</label>'
                        : '<label class="badge badge-warning">Pending</label>',
                    'action' => AdminDataTable::actions(
                        route('admin.users.edit', $user),
                        route('admin.users.destroy', $user),
                        'Delete user?',
                        'This user will be removed.'
                    ),
                ];
            },
            ['category' => ['name']]
        );
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

        unset($data['email'], $data['referral_code']);

        $data['status'] = $data['status'] ?? 1;
        $data['email_verified_at'] = ! empty($data['email_verified']) ? ($user->email_verified_at ?? now()) : null;

        if (isset($data['profile']) && $data['profile'] instanceof UploadedFile) {
            $this->deleteProfile($user->profile);
            $data['profile'] = $this->uploadProfile($data['profile']);
        } else {
            unset($data['profile']);
        }

        unset($data['email_verified']);

        $user->update($data);

        if (in_array($user->type, ['user', 'agent'], true)) {
            $this->ensureCompanyProfile($user->fresh());
        }

        return $user;
    }

    public function updateStatus(User $user, int $status): User
    {
        $user->update(['status' => $status]);

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
