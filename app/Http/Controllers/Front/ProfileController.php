<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\UpdateProfileRequest;
use App\Models\Category;
use App\Models\CompanyProfileDocument;
use App\Models\Country;
use App\Models\SubCategory;
use App\Services\Front\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $profileService) {}

    public function show(Request $request)
    {
        $user = $request->user()->load(['companyProfile.profileDocuments', 'category', 'userPlans.plan']);
        $profile = $user->companyProfile;

        abort_unless($profile, 404);

        $categories = Category::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        $subCategories = SubCategory::query()
            ->where('category_id', $user->category_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        $profileDocuments = $profile->profileDocuments;

        $completion = $this->profileService->completion($user);

        return view('front.users.profile', [
            'user' => $user,
            'profile' => $profile,
            'categories' => $categories,
            'subCategories' => $subCategories,
            'completionPercent' => $completion['percent'],
            'completionLevel' => $completion['level'],
            'completionFilled' => $completion['filled'],
            'completionTotal' => $completion['total'],
            'documentStatus' => $profile->documentVerificationStatus(),
            'planName' => $this->profileService->planName($user),
            'previewUrl' => route('front.profile.show', $profile->slug),
            'countries' => Country::query()->orderBy('name')->get(['id', 'name']),
            'individualDocuments' => CompanyProfileDocument::formRows(
                old('individual_documents'),
                $profileDocuments->where('business_type', CompanyProfileDocument::TYPE_INDIVIDUAL)
            ),
            'businessDocuments' => CompanyProfileDocument::formRows(
                old('business_documents'),
                $profileDocuments->where('business_type', CompanyProfileDocument::TYPE_BUSINESS)
            ),
            'individualDocumentTypes' => CompanyProfileDocument::namesFor(CompanyProfileDocument::TYPE_INDIVIDUAL),
            'businessDocumentTypes' => CompanyProfileDocument::namesFor(CompanyProfileDocument::TYPE_BUSINESS),
            'documentClientConfig' => CompanyProfileDocument::clientConfig(),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->profileService->update($request->user(), $request->profilePayload());

        return redirect()
            ->route('front.users.profile')
            ->with('success', 'Profile saved successfully.');
    }
}
