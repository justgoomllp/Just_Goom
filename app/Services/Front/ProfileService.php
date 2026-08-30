<?php

namespace App\Services\Front;

use App\Models\CompanyProfile;
use App\Models\CompanyProfileDocument;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProfileService
{
    public function update(User $user, array $data): User
    {
        $subCategoryIds = array_values(array_unique(array_map('strval', $data['sub_category_id'] ?? [])));

        $validCount = SubCategory::query()
            ->whereIn('id', $subCategoryIds)
            ->where('category_id', $data['category_id'])
            ->where('status', 1)
            ->count();

        if ($subCategoryIds === [] || $validCount !== count($subCategoryIds)) {
            throw ValidationException::withMessages([
                'sub_category_id' => 'Selected sub category does not belong to the chosen category.',
            ]);
        }

        return DB::transaction(function () use ($user, $data, $subCategoryIds) {
            $user->update([
                'category_id' => $data['category_id'],
                'sub_category_id' => implode(',', $subCategoryIds),
                'phone' => $data['phone'],
                'email' => $data['email'],
                'country' => $data['country'],
                'state' => $data['state'],
                'city' => $data['city'],
            ]);

            $profile = $user->companyProfile;

            if (! $profile) {
                throw ValidationException::withMessages([
                    'company_name' => 'Company profile not found. Please contact support.',
                ]);
            }

            $profileData = [
                'company_name' => $data['company_name'],
                'tagline' => $data['tagline'] ?? null,
                'business_desc' => $data['business_desc'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'] ?? null,
                'country' => $data['country'],
                'state' => $data['state'],
                'city' => $data['city'],
                'zipcode' => $data['zipcode'] ?? null,
                'business_hours' => $data['business_hours'] ?? null,
                'social_website' => $data['social_website'] ?? null,
                'social_facebook' => $data['social_facebook'] ?? null,
                'social_twitter' => $data['social_twitter'] ?? null,
                'social_linkedin' => $data['social_linkedin'] ?? null,
            ];

            if (($data['logo'] ?? null) instanceof UploadedFile) {
                $this->deleteLogo($profile->logo);
                $profileData['logo'] = $this->uploadLogo($data['logo']);
            }

            $profile->update($profileData);

            $this->syncProfileDocuments($user, $profile, $data);

            return $user->fresh(['companyProfile.profileDocuments', 'category', 'userPlans.plan']);
        });
    }

    /**
     * About-section completeness: Team, Services, Products, Projects,
     * Documents, Videos, My Blog, My Advertisement.
     *
     * @return array{filled: int, total: int, percent: int, level: string}
     */
    public function completion(User $user): array
    {
        return $this->completionFromFilledCount($this->aboutSectionsFilledCount($user));
    }

    public function completionPercent(User $user): int
    {
        return $this->completion($user)['percent'];
    }

    public function completionLevel(User $user): string
    {
        return $this->completion($user)['level'];
    }

    /**
     * Use withCount aliases from PublicProfileListingService (no extra queries).
     *
     * @return array{filled: int, total: int, percent: int, level: string}
     */
    public function completionFromLoadedCounts(User $user): array
    {
        $filled = count(array_filter([
            (int) ($user->teams_count ?? 0) > 0,
            (int) ($user->service_items_count ?? $user->services_count ?? 0) > 0,
            (int) ($user->product_items_count ?? 0) > 0,
            (int) ($user->profile_projects_count ?? 0) > 0,
            (int) ($user->documents_count ?? 0) > 0,
            (int) ($user->videos_count ?? 0) > 0,
            (int) ($user->articles_count ?? 0) > 0,
            (int) ($user->offers_count ?? 0) > 0,
        ]));

        return $this->completionFromFilledCount($filled);
    }

    public function aboutSectionsFilledCount(User $user): int
    {
        return count(array_filter([
            $user->teams()->exists(),
            $user->services()->where('type', 'service')->exists(),
            $user->services()->where('type', 'product')->exists(),
            $user->projects()->exists(),
            $user->documents()->exists(),
            $user->videos()->exists(),
            $user->articles()->exists(),
            $user->offers()->exists(),
        ]));
    }

    /**
     * 0–2 sections → 0–40% (red), 3–5 → 41–70% (yellow), 6–8 → 71–100% (green).
     *
     * @return array{filled: int, total: int, percent: int, level: string}
     */
    public function completionFromFilledCount(int $filled): array
    {
        $total = 8;
        $filled = max(0, min($filled, $total));

        if ($filled <= 2) {
            $percent = (int) round(($filled / 2) * 40);
        } elseif ($filled <= 5) {
            $percent = (int) round(41 + (($filled - 3) / 2) * 29);
        } else {
            $percent = (int) round(71 + (($filled - 6) / 2) * 29);
        }

        return [
            'filled' => $filled,
            'total' => $total,
            'percent' => $percent,
            'level' => $this->levelFromPercent($percent),
        ];
    }

    public function levelFromPercent(int $percent): string
    {
        if ($percent <= 40) {
            return 'low';
        }

        if ($percent <= 70) {
            return 'medium';
        }

        return 'complete';
    }

    public function planName(User $user): string
    {
        $plan = $user->userPlans()
            ->with('plan')
            ->latest('purchase_date')
            ->first()
            ?->plan;

        return $plan?->name ? $plan->name.' Plan' : 'Free Plan';
    }

    private function syncProfileDocuments(User $user, CompanyProfile $profile, array $data): void
    {
        $this->syncDocumentGroup(
            $user,
            $profile,
            CompanyProfileDocument::TYPE_INDIVIDUAL,
            $data['individual_documents'] ?? []
        );

        $this->syncDocumentGroup(
            $user,
            $profile,
            CompanyProfileDocument::TYPE_BUSINESS,
            $data['business_documents'] ?? []
        );
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     */
    private function syncDocumentGroup(User $user, CompanyProfile $profile, string $businessType, array $rows): void
    {
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $id = isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null;
            $existing = $id
                ? $profile->profileDocuments()->whereKey($id)->where('business_type', $businessType)->first()
                : null;

            if (! empty($row['_destroy'])) {
                if ($existing) {
                    $this->deleteStoredFile($existing->front_image);
                    $this->deleteStoredFile($existing->back_image);
                    $existing->delete();
                }

                continue;
            }

            $documentName = trim((string) ($row['document_name'] ?? ''));
            $value = trim((string) ($row['value'] ?? ''));

            if ($documentName === '' && $value === ''
                && ! (($row['front_image'] ?? null) instanceof UploadedFile)
                && ! (($row['back_image'] ?? null) instanceof UploadedFile)
            ) {
                continue;
            }

            $payload = [
                'company_profile_id' => $profile->id,
                'user_id' => $user->id,
                'business_type' => $businessType,
                'document_name' => $documentName,
                'value' => $value,
            ];

            $contentChanged = false;

            if (($row['front_image'] ?? null) instanceof UploadedFile) {
                $this->deleteStoredFile($existing?->front_image);
                $payload['front_image'] = $this->uploadDocumentImage($row['front_image']);
                $contentChanged = true;
            }

            if (($row['back_image'] ?? null) instanceof UploadedFile) {
                $this->deleteStoredFile($existing?->back_image);
                $payload['back_image'] = $this->uploadDocumentImage($row['back_image']);
                $contentChanged = true;
            }

            if ($existing) {
                $contentChanged = $contentChanged
                    || $existing->document_name !== $documentName
                    || $existing->value !== $value;

                if ($contentChanged) {
                    $payload['is_approved'] = CompanyProfileDocument::APPROVAL_PENDING;
                }

                $existing->update($payload);
            } else {
                $payload['is_approved'] = CompanyProfileDocument::APPROVAL_PENDING;
                $profile->profileDocuments()->create($payload);
            }
        }
    }

    private function uploadDocumentImage(UploadedFile $file): string
    {
        $email = auth()->user()->email;
        $destination = public_path('uploads/'.$email.'/company-documents');
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0777, true);
        }

        $filename = time().'-'.Str::random(12).'.'.$file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/'.$email.'/company-documents/'.$filename;
    }

    private function uploadLogo(UploadedFile $file): string
    {
        $email = auth()->user()->email;
        $destination = public_path('uploads/'.$email.'/company-logos');
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0777, true);
        }

        $filename = time().'-'.Str::random(12).'.'.$file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/'.$email.'/company-logos/'.$filename;
    }

    private function deleteLogo(?string $logo): void
    {
        $this->deleteStoredFile($logo);
    }

    private function deleteStoredFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path($path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
