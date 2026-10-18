<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\StorePublicInquiryRequest;
use App\Services\Front\CategoryCatalogService;
use App\Services\Front\DocumentService;
use App\Services\Front\InquiryService;
use App\Services\Front\ProfileService;
use App\Services\Front\PublicProfileListingService;
use App\Services\Front\ServiceService;
use App\Services\Front\TeamService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class PublicProfileController extends Controller
{
    public function __construct(
        private PublicProfileListingService $listingService,
        private CategoryCatalogService $categoryCatalogService,
        private ProfileService $profileService,
        private TeamService $teamService,
        private ServiceService $serviceService,
        private DocumentService $documentService,
    ) {
    }

    public function index()
    {
        $stats = $this->listingService->stats();

        return view('front.pages.all_profiles', [
            'companyProfiles' => $this->listingService->listForFrontend(),
            'profileStats' => $stats,
            'categorySectors' => $this->categoryCatalogService->sectorsForFrontend(),
            'filterCategories' => $this->categoryCatalogService->activeCategories(),
            'filterCities' => $this->listingService->citiesForFilter(),
        ]);
    }

    public function show(string $slug)
    {
        $profile = $this->listingService->findPublicProfile($slug);
        $user = $profile->user;

        $teams = $this->teamService->listForPublicProfile($user);
        $services = $this->serviceService->listForPublicProfile($user, 'service');
        $products = $this->serviceService->listForPublicProfile($user, 'product');
        $documents = $this->documentService->listForPublicProfile($user);
        $projects = $user->projects()->where('status', 1)->latest()->get();
        $user->setRelation('projects', $projects);
        $articles = $user->articles()->where('status', 'published')->latest('published_at')->get();
        $offers = $user->offers()->active()->orderByDesc('is_featured')->orderByDesc('created_at')->get();
        $videos = collect(DB::table('videos')
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->latest()
            ->get());

        $activities = collect()
            ->merge($projects->take(3)->map(fn ($item) => [
                'text' => 'Published project “'.$item->title.'”',
                'time' => $item->created_at?->diffForHumans() ?? '',
                'at' => $item->created_at,
            ]))
            ->merge($articles->take(3)->map(fn ($item) => [
                'text' => 'Published blog “'.$item->title.'”',
                'time' => ($item->published_at ?? $item->created_at)?->diffForHumans() ?? '',
                'at' => $item->published_at ?? $item->created_at,
            ]))
            ->merge($videos->take(2)->map(fn ($item) => [
                'text' => 'Added video “'.$item->title.'”',
                'time' => Carbon::parse($item->created_at)->diffForHumans(),
                'at' => $item->created_at,
            ]))
            ->merge($teams->take(2)->map(fn ($item) => [
                'text' => 'Added team member '.$item->name,
                'time' => $item->created_at?->diffForHumans() ?? '',
                'at' => $item->created_at,
            ]))
            ->sortByDesc('at')
            ->take(6)
            ->values();

        $completion = $this->profileService->completion($user);

        return view('front.pages.profile', [
            'profile' => $profile,
            'user' => $user,
            'teams' => $teams,
            'services' => $services,
            'products' => $products,
            'documents' => $documents,
            'projects' => $projects,
            'articles' => $articles,
            'offers' => $offers,
            'videos' => $videos,
            'activities' => $activities,
            'bannerUrl' => \App\Support\ProfileBanner::url($profile, $user),
            'completionPercent' => $completion['percent'],
            'completionLevel' => $completion['level'],
            'planName' => $this->profileService->planName($user),
            'isOwner' => auth()->check() && auth()->id() === $user->id,
            'profileUrl' => route('front.profile.show', $profile->slug),
            'qrUrl' => route('front.profile.qr', ['slug' => $profile->slug, 'size' => 180]),
            'qrDownloadUrl' => route('front.profile.qr', ['slug' => $profile->slug, 'size' => 400]),
            'justgoomLogoUrl' => asset('front/assets/images/justgoom-logo.png'),
        ]);
    }

    public function storeInquiry(StorePublicInquiryRequest $request, string $slug, InquiryService $inquiryService)
    {
        $profile = $this->listingService->findPublicProfile($slug);
        $user = $profile->user;
        $successMessage = 'Thank you. Your inquiry has been sent.';

        if (auth()->check() && (int) auth()->id() === (int) $user->id) {
            $message = 'You cannot send an inquiry to your own profile.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()
                ->to(route('front.profile.show', $profile->slug).'#contact')
                ->with('error', $message);
        }

        if (filled($request->input('website'))) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $successMessage]);
            }

            return redirect()
                ->to(route('front.profile.show', $profile->slug).'#contact')
                ->with('success', $successMessage);
        }

        try {
            $inquiryService->createForProfile($user, [
                'sender_name' => $request->validated('name'),
                'sender_company' => $request->validated('company_name'),
                'sender_email' => $request->validated('email'),
                'sender_phone' => $request->validated('phone'),
                'subject' => $request->validated('subject'),
                'message' => $request->validated('message'),
            ]);
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                throw $e;
            }

            return redirect()
                ->to(route('front.profile.show', $profile->slug).'#contact')
                ->withErrors($e->errors())
                ->withInput();
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => $successMessage]);
        }

        return redirect()
            ->to(route('front.profile.show', $profile->slug).'#contact')
            ->with('success', $successMessage);
    }

    public function qrImage(string $slug, Request $request)
    {
        $profile = $this->listingService->findPublicProfile($slug);
        $size = max(120, min(600, (int) $request->query('size', 400)));
        $profileUrl = route('front.profile.show', $profile->slug);
        // Standard black & white QR (high error correction for center JG).
        $apiUrl = 'https://api.qrserver.com/v1/create-qr-code/'
            .'?size='.$size.'x'.$size
            .'&margin=12'
            .'&ecc=H'
            .'&color=000000'
            .'&bgcolor=FFFFFF'
            .'&data='.urlencode($profileUrl);

        $response = Http::timeout(10)->get($apiUrl);
        if (! $response->successful()) {
            abort(502, 'Unable to generate QR code.');
        }

        $png = $response->body();

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
            'Access-Control-Allow-Origin' => '*',
        ]);
    }

    public function redirectLegacy(Request $request)
    {
        if ($request->filled('slug')) {
            return redirect()->route('front.profile.show', $request->query('slug'));
        }

        return redirect()->route('front.all-profiles');
    }
}
