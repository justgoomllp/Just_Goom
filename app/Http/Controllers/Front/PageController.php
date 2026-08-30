<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Offer;
use App\Services\Front\CategoryCatalogService;
use App\Services\Front\PublicProfileListingService;

class PageController extends Controller
{
    private const PUBLIC_VIEW_MAP = [
        'all-profiles' => 'all_profiles',
    ];

    private const USER_VIEW_MAP = [
        'dashboard' => 'index',
    ];

    public function __construct(
        private CategoryCatalogService $categoryCatalogService,
        private PublicProfileListingService $publicProfileListingService,
    ) {
    }

    public function home()
    {
        return view('front.pages.index', [
            'categorySectors' => $this->categoryCatalogService->sectorsForFrontend(),
            'companyProfiles' => $this->publicProfileListingService->listForFrontend(),
            'majorCountries' => $this->majorCountriesForHome(),
            'runningOffers' => Offer::with('user.companyProfile')
                ->active()
                ->orderByDesc('is_featured')
                ->orderByDesc('created_at')
                ->take(6)
                ->get(),
            'advertisements' => Advertisement::active()
                ->forPosition('homepage')
                ->orderByDesc('priority')
                ->take(3)
                ->get(),
            'homeArticles' => Article::with(['user.companyProfile', 'user.category'])
                ->published()
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->take(6)
                ->get(),
        ]);
    }

    private function majorCountriesForHome(): array
    {
        return [
            [
                'name' => 'India',
                'image' => 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=640&h=480&fit=crop&q=80',
                'landmark' => 'Taj Mahal',
            ],
            [
                'name' => 'United States',
                'image' => 'https://images.unsplash.com/photo-1485738422979-f5c462d49f74?w=640&h=480&fit=crop&q=80',
                'landmark' => 'Statue of Liberty',
            ],
            [
                'name' => 'United Arab Emirates',
                'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=640&h=480&fit=crop&q=80',
                'landmark' => 'Burj Khalifa',
            ],
            [
                'name' => 'United Kingdom',
                'image' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=640&h=480&fit=crop&q=80',
                'landmark' => 'Big Ben',
            ],
            [
                'name' => 'Australia',
                'image' => 'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?w=640&h=480&fit=crop&q=80',
                'landmark' => 'Sydney Opera House',
            ],
            [
                'name' => 'Canada',
                'image' => 'https://images.unsplash.com/photo-1517935706615-2717063c2225?w=640&h=480&fit=crop&q=80',
                'landmark' => 'Toronto skyline',
            ],
        ];
    }

    public function publicPage(string $page)
    {
        $view = $this->resolvePublicView($page);

        abort_unless(view()->exists($view), 404);

        return view($view);
    }

    public function userPage(string $page = 'dashboard')
    {
        $view = $this->resolveUserView($page);

        abort_unless(view()->exists($view), 404);

        return view($view);
    }

    private function resolvePublicView(string $page): string
    {
        $viewName = self::PUBLIC_VIEW_MAP[$page] ?? $page;

        return 'front.pages.'.$viewName;
    }

    private function resolveUserView(string $page): string
    {
        $viewName = self::USER_VIEW_MAP[$page] ?? $page;

        return 'front.users.'.$viewName;
    }
}
