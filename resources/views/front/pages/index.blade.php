@extends('front.layouts.app')

@section('title', 'Just Goom LLP — Global B2B Business Discovery Platform')
@section('meta_description', 'JustGoom — Connect with verified business profiles, browse categories, and grow your business across India.')
@section('body_attrs', 'class="home-b2b" data-page="home"')

@section('content')
<!-- B2B Hero -->
  <section class="hero b2b-hero">
    <div class="container">
      <div class="b2b-hero-inner">
        <div class="b2b-hero-content">
          <div class="hero-badge">Global B2B Business Discovery Platform</div>
          <h1>Connect Buyers with Verified Business Profiles</h1>
          <p>Discover trusted suppliers, service providers, and local businesses — all in one place. Search by category, city, or business name.</p>

          <div class="b2b-search-box">
            <div class="b2b-search-tabs">
              <button type="button" class="active" data-tab="find">Find Businesses</button>
              <button type="button" data-tab="post">Post Requirement</button>
            </div>
            <form class="b2b-search-form" action="{{ route('front.all-profiles') }}" method="get">
              <div class="b2b-search-field">
                <label for="b2bKeyword">What are you looking for?</label>
                <input type="text" id="b2bKeyword" name="q" placeholder="Business name, category, or service...">
              </div>
              <div class="b2b-search-field b2b-search-location">
                <label for="b2bCity">City</label>
                <input type="text" id="b2bCity" name="city" placeholder="Enter city...">
              </div>
              <button type="submit" class="btn btn-accent b2b-search-btn">Search</button>
            </form>
          </div>

          <div class="b2b-hero-actions">
            <a href="{{ route('front.all-profiles') }}" class="btn btn-primary btn-lg">Browse All Profiles</a>
            <a href="{{ route('front.register') }}" class="btn btn-outline-light btn-lg">Register Your Business</a>
          </div>

          <div class="b2b-trust-badges">
            <span>✓ Verified Profiles</span>
            <span>✓ Free Business Listing</span>
            <span>✓ Global Reach</span>
          </div>
        </div>
        <div class="b2b-hero-visual">
          <div class="b2b-hero-card b2b-hero-card-1">
            <span class="b2b-card-icon">🏢</span>
            <strong>30+</strong>
            <span>Verified Businesses</span>
          </div>
          <div class="b2b-hero-card b2b-hero-card-2">
            <span class="b2b-card-icon">📂</span>
            <strong>24+</strong>
            <span>Business Sectors</span>
          </div>
          <div class="b2b-hero-card b2b-hero-card-3">
            <span class="b2b-card-icon">📍</span>
            <strong>50+</strong>
            <span>Cities Covered</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- All Categories — Globy-style sectors -->
  <section class="globy-sectors-section home-sectors" id="categories">
    <div class="container">
      <div class="globy-sectors-header">
        <div>
          <h2 class="globy-sectors-title">Wide range of business sectors</h2>
          <p class="globy-sectors-subtitle">Explore categories with offers from verified suppliers across India</p>
        </div>
      </div>
      <div class="globy-sectors-grid" id="homeSectorsGrid"></div>
      <div class="globy-sectors-footer">
        <span id="homeSectorsFooterCount"></span>
        <a href="{{ route('front.categories') }}" class="globy-view-all">View all categories →</a>
      </div>
    </div>
  </section>

  <!-- Company Profiles -->
  <section class="company-profiles-section" id="companies">
    <div class="container">
      <div class="section-header">
        <div>
          <h2 class="section-title">Featured Business Profiles</h2>
          <p class="section-subtitle" id="homeCompanySubtitle">Browse verified business profiles — showing 12 featured listings</p>
        </div>
        <a href="{{ route('front.all-profiles') }}" class="view-all-link">View All →</a>
      </div>

      <div class="company-toolbar">
        <div class="company-search-wrap">
          <span class="company-search-icon">🔍</span>
          <input type="text" id="homeCompanySearch" placeholder="Search for name or designation...">
        </div>
        <div class="company-toolbar-actions">
          <div class="view-toggle">
            <button type="button" id="homeViewGrid" class="active" title="Grid view">▦</button>
            <button type="button" id="homeViewList" title="List view">☰</button>
          </div>
          <button type="button" class="btn-filters" id="homeFiltersBtn">+ Filters</button>
        </div>
      </div>

      <p class="company-count" id="homeCompanyCount">Showing 12 of 12 company profiles</p>
      <div class="company-grid" id="homeCompanyGrid"></div>
    </div>
  </section>

  <!-- Profile Steps Flow -->
  <section class="profile-flow-section">
    <div class="container">
      <div class="profile-flow-header">
        <div>
          <h2 class="section-title">How the Profile Journey Works</h2>
          <p class="section-subtitle">From registration to listing to inquiry — grow your business in 3 simple steps</p>
        </div>
        <div class="profile-flow-tabs" role="tablist">
          <button type="button" class="active" data-flow="seller" role="tab" aria-selected="true">For Sellers</button>
          <button type="button" data-flow="buyer" role="tab" aria-selected="false">For Buyers</button>
        </div>
      </div>

      <!-- Seller Flow -->
      <div class="profile-flow-track active" id="flowSeller" data-flow-panel="seller">
        <div class="profile-flow-steps">
          <div class="profile-flow-step">
            <div class="profile-flow-step-badge">Step 1</div>
            <div class="profile-flow-step-icon">📝</div>
            <h3>Register Profile</h3>
            <p>Create your free business account. Add company details, category, services, and contact information.</p>
            <a href="{{ route('front.register') }}" class="profile-flow-link">Register Now →</a>
          </div>
          <div class="profile-flow-connector" aria-hidden="true">
            <span class="profile-flow-line"></span>
            <span class="profile-flow-arrow">→</span>
          </div>
          <div class="profile-flow-step">
            <div class="profile-flow-step-badge">Step 2</div>
            <div class="profile-flow-step-icon">📋</div>
            <h3>Get Listed</h3>
            <p>Your profile goes live on JustGoom. Appear in category search, city filters, and featured listings.</p>
            <a href="{{ route('front.all-profiles') }}" class="profile-flow-link">View Listings →</a>
          </div>
          <div class="profile-flow-connector" aria-hidden="true">
            <span class="profile-flow-line"></span>
            <span class="profile-flow-arrow">→</span>
          </div>
          <div class="profile-flow-step profile-flow-step-highlight">
            <div class="profile-flow-step-badge">Step 3</div>
            <div class="profile-flow-step-icon">📩</div>
            <h3>Receive Inquiries</h3>
            <p>Buyers discover your profile and send inquiries. Connect directly and convert leads into customers.</p>
            <a href="{{ route('front.contact') }}" class="profile-flow-link">Learn More →</a>
          </div>
        </div>
        <div class="profile-flow-cta">
          <a href="{{ route('front.register') }}" class="btn btn-accent btn-lg">List Your Business Free</a>
          <span class="profile-flow-cta-note">100% Free · No credit card required</span>
        </div>
      </div>

      <!-- Buyer Flow -->
      <div class="profile-flow-track" id="flowBuyer" data-flow-panel="buyer" hidden>
        <div class="profile-flow-steps">
          <div class="profile-flow-step">
            <div class="profile-flow-step-badge">Step 1</div>
            <div class="profile-flow-step-icon">🔍</div>
            <h3>Browse Profiles</h3>
            <p>Search by category, city, or business name. Explore verified profiles across 24+ business sectors.</p>
            <a href="{{ route('front.all-profiles') }}" class="profile-flow-link">Browse Profiles →</a>
          </div>
          <div class="profile-flow-connector" aria-hidden="true">
            <span class="profile-flow-line"></span>
            <span class="profile-flow-arrow">→</span>
          </div>
          <div class="profile-flow-step">
            <div class="profile-flow-step-badge">Step 2</div>
            <div class="profile-flow-step-icon">👁️</div>
            <h3>View Listing Details</h3>
            <p>Check services, location, verification status, and business info before you reach out.</p>
            <a href="{{ route('front.all-profiles') }}" class="profile-flow-link">View a Profile →</a>
          </div>
          <div class="profile-flow-connector" aria-hidden="true">
            <span class="profile-flow-line"></span>
            <span class="profile-flow-arrow">→</span>
          </div>
          <div class="profile-flow-step profile-flow-step-highlight">
            <div class="profile-flow-step-badge">Step 3</div>
            <div class="profile-flow-step-icon">💬</div>
            <h3>Send Inquiry</h3>
            <p>Contact the business directly or post your requirement to get matched with the right supplier.</p>
            <a href="{{ route('front.contact') }}" class="profile-flow-link">Post Requirement →</a>
          </div>
        </div>
        <div class="profile-flow-cta">
          <a href="{{ route('front.contact') }}" class="btn btn-primary btn-lg">Post Your Requirement</a>
          <span class="profile-flow-cta-note">Get matched with verified businesses</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Location Selection -->
  <section class="location-selector-section" id="location-selector">
    <div class="container">
      <div class="location-selector-header">
        <h2 class="section-title">Find Businesses by Location</h2>
        <p class="section-subtitle">Select your country, state, and city to discover local businesses worldwide</p>
      </div>
      <div class="location-selector-form">
        <div class="location-selector-fields">
          <div class="location-field">
            <label for="locCountry">Country</label>
            <select id="locCountry" class="location-select">
              <option value="">Select Country</option>
            </select>
          </div>
          <div class="location-field">
            <label for="locState">State / Province</label>
            <select id="locState" class="location-select" disabled>
              <option value="">Select State</option>
            </select>
          </div>
          <div class="location-field">
            <label for="locCity">City</label>
            <select id="locCity" class="location-select" disabled>
              <option value="">Select City</option>
            </select>
          </div>
          <div class="location-field location-field-btn">
            <button type="button" id="locSearchBtn" class="btn btn-accent btn-lg" disabled>Search Businesses</button>
          </div>
        </div>
      </div>
      <div class="major-countries-grid" style="margin-top:32px;">
        @foreach($majorCountries as $country)
          <a href="{{ route('front.all-profiles', ['country' => $country['name']]) }}" class="major-country-card">
            <div class="major-country-thumb">
              <img src="{{ str_starts_with($country['image'], 'http') ? $country['image'] : asset('front/assets/images/'.$country['image']) }}"
                   alt="{{ $country['name'] }} — {{ $country['landmark'] ?? 'business profiles' }}"
                   loading="lazy">
            </div>
            <span class="major-country-name">{{ $country['name'] }}</span>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Running Offers & Advertisements -->
  <section class="offers-section" id="running-offers">
    <div class="container">
      <div class="section-header">
        <div>
          <h2 class="section-title">Running Offers & Promotions</h2>
          <p class="section-subtitle">Exclusive deals and promotions from registered businesses</p>
        </div>
        <a href="{{ route('front.all-profiles') }}" class="view-all-link">Browse All →</a>
      </div>

      @php
        $fallbackOfferImages = [
          asset('front/assets/images/cat-business.jpg'),
          asset('front/assets/images/cat-food.jpg'),
          asset('front/assets/images/cat-health.jpg'),
          asset('front/assets/images/cat-real-estate.jpg'),
        ];
        $defaultLogo = asset('front/assets/images/justgoom-logo.png');

        $offerCards = collect($runningOffers ?? [])->map(function ($offer, $index) use ($fallbackOfferImages, $defaultLogo) {
          $company = $offer->user?->companyProfile;
          $companyName = $company?->company_name
            ?: trim(($offer->user?->fname ?? '') . ' ' . ($offer->user?->lname ?? ''))
            ?: 'JustGoom Member';
          $companyTagLine = $company?->tagline ?: $companyName;
          $offerUrl = $company?->slug
            ? route('front.profile.show', $company->slug) . '#offers'
            : route('front.all-profiles');

          return [
            'title' => $offer->title,
            'offer_url' => $offerUrl,
            'tagline' => $companyTagLine,
            'logo' => $company?->logo ? asset($company->logo) : $defaultLogo,
            'logo_alt' => $companyName,
            'image' => $offer->banner_image
              ? asset($offer->banner_image)
              : $fallbackOfferImages[$index % count($fallbackOfferImages)],
            'company' => $companyName,
          ];
        })->values();
      @endphp

      @if($offerCards->isNotEmpty())
      <div class="offers-promo-carousel" id="offersCarousel">
        <button type="button" class="offers-promo-nav prev" aria-label="Previous offer">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="offers-promo-track" id="offersTrack">
          @foreach($offerCards as $offer)
          <a href="{{ $offer['offer_url'] }}" class="offer-promo-card" @if(\Illuminate\Support\Str::startsWith($offer['offer_url'], ['http://', 'https://'])) target="_blank" rel="noopener" @endif>
            <div class="offer-promo-media">
              <img class="offer-promo-bg" src="{{ $offer['image'] }}" alt="" loading="lazy" aria-hidden="true">
              <div class="offer-promo-overlay" aria-hidden="true"></div>
              <span class="offer-promo-ad">Sponsored</span>
            </div>
            <div class="offer-promo-content">
              <div class="offer-promo-brand">
                <div class="offer-promo-logo">
                  <img src="{{ $offer['logo'] }}" alt="{{ $offer['logo_alt'] }}">
                </div>
                <span class="offer-promo-company" title="{{ $offer['company'] }}">{{ \Illuminate\Support\Str::limit($offer['company'], 28) }}</span>
              </div>
              <h3 class="offer-promo-title" title="{{ $offer['title'] }}">{{ \Illuminate\Support\Str::limit($offer['title'], 55) }}</h3>
              <p class="offer-promo-tagline" title="{{ $offer['tagline'] }}">{{ \Illuminate\Support\Str::limit($offer['tagline'], 80) }}</p>
              <span class="offer-promo-cta">View offer <span aria-hidden="true">→</span></span>
            </div>
          </a>
          @endforeach
        </div>
        <button type="button" class="offers-promo-nav next" aria-label="Next offer">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="offers-promo-dots" id="offersDots" role="tablist" aria-label="Offer slides"></div>
      </div>
      @else
      <p class="section-subtitle" style="margin:0;">No running offers right now. Check back soon for new promotions.</p>
      @endif

      @if(!empty($advertisements) && count($advertisements) > 0)
      <div class="ads-banner-row" style="margin-top:28px;">
        @foreach($advertisements as $ad)
        <a href="{{ $ad->link_url ?? '#' }}" target="_blank" class="ad-banner-card" rel="noopener">
          <img src="{{ asset('storage/' . $ad->banner_image) }}" alt="{{ $ad->title }}" loading="lazy">
        </a>
        @endforeach
      </div>
      @endif
    </div>
  </section>

  <!-- Stats Bar -->
  <div class="stats-bar">
    <div class="container stats-grid">
      <div class="stat-item">
        <div class="stat-num">30+</div>
        <div class="stat-label">Verified Businesses</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">24+</div>
        <div class="stat-label">Business Sectors</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">50+</div>
        <div class="stat-label">Cities Covered</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">10K+</div>
        <div class="stat-label">Monthly Inquiries</div>
      </div>
    </div>
  </div>

  <!-- Blog / Articles -->
  <section class="section home-blog-section" id="articles">
    <div class="container">
      <div class="section-header">
        <div>
          <h2 class="section-title">Latest Articles & Insights</h2>
          <p class="section-subtitle">Business tips and promotional articles from verified members</p>
        </div>
        <a href="{{ route('front.articles') }}" class="view-all-link">View All →</a>
      </div>

      @php
        $fallbackBlogImages = [
          asset('front/assets/images/blog-1.jpg'),
          asset('front/assets/images/blog-2.jpg'),
          asset('front/assets/images/blog-3.jpg'),
        ];

        $staticBlogs = [
          [
            'title' => 'Why 22K Gold is the Smart Choice for Wedding Jewellery',
            'excerpt' => 'Purity, making charges, and resale value explained for B2B buyers and retailers.',
            'author' => 'Shree Gold Jewellers',
            'initials' => 'SG',
            'tag' => 'Jewellery',
            'date' => 'May 28, 2026',
            'read' => '5 min read',
            'image' => asset('front/assets/images/blog-1.jpg'),
          ],
          [
            'title' => 'Commercial Property Investment Guide for SMEs',
            'excerpt' => 'What business owners should know before investing in office or warehouse space.',
            'author' => 'Mehta Real Estate',
            'initials' => 'MR',
            'tag' => 'Real Estate',
            'date' => 'May 25, 2026',
            'read' => '8 min read',
            'image' => asset('front/assets/images/cat-real-estate.jpg'),
          ],
          [
            'title' => '5 Ways to Grow Your Local Business Online in 2026',
            'excerpt' => 'From listing optimization to customer engagement — practical MSME strategies.',
            'author' => 'Amit Mehta Consulting',
            'initials' => 'AM',
            'tag' => 'Business',
            'date' => 'May 22, 2026',
            'read' => '4 min read',
            'image' => asset('front/assets/images/blog-3.jpg'),
          ],
        ];

        $dynamicBlogs = collect($homeArticles ?? [])->map(function ($article, $index) use ($fallbackBlogImages) {
          $company = $article->user?->companyProfile;
          $authorName = $company?->company_name
            ?: trim(($article->user?->fname ?? '') . ' ' . ($article->user?->lname ?? ''))
            ?: 'JustGoom Member';
          $initials = collect(preg_split('/\s+/', trim($authorName)))
            ->filter()
            ->take(2)
            ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
            ->implode('') ?: 'JG';
          $published = $article->published_at ?? $article->created_at;

          return [
            'title' => $article->title,
            'excerpt' => \Illuminate\Support\Str::limit(strip_tags($article->body), 110),
            'author' => $authorName,
            'initials' => $initials,
            'tag' => $article->user?->category?->name ?? 'Business',
            'date' => $published?->format('M j, Y') ?? '',
            'read' => max(1, (int) ceil(str_word_count(strip_tags($article->body)) / 200)) . ' min read',
            'image' => $article->featured_image
              ? asset($article->featured_image)
              : $fallbackBlogImages[$index % count($fallbackBlogImages)],
            'url' => route('front.articles.show', $article->slug),
          ];
        });

        $blogCards = $dynamicBlogs
          ->concat(collect($staticBlogs)->map(fn ($blog) => array_merge($blog, [
            'url' => route('front.articles'),
          ])))
          ->take(6)
          ->values();
      @endphp

      <div class="blog-carousel" id="blogCarousel">
        <button type="button" class="blog-carousel-nav prev" aria-label="Previous article">‹</button>
        <div class="blog-carousel-track" id="blogTrack">
          @foreach($blogCards as $blog)
          <a href="{{ $blog['url'] }}" class="blog-card blog-card-link">
            <div class="blog-thumb">
              <img src="{{ $blog['image'] }}" alt="{{ $blog['title'] }}" loading="lazy">
            </div>
            <div class="blog-body">
              <div class="article-author-bar">
                <span class="blog-author-avatar">{{ $blog['initials'] }}</span>
                <div>
                  <strong>{{ $blog['author'] }}</strong>
                </div>
              </div>
              <span class="blog-tag">{{ $blog['tag'] }}</span>
              <h3>{{ $blog['title'] }}</h3>
              <p>{{ $blog['excerpt'] }}</p>
              <div class="blog-footer">
                <span>{{ $blog['date'] }}</span>
                <span>{{ $blog['read'] }}</span>
              </div>
            </div>
          </a>
          @endforeach
        </div>
        <button type="button" class="blog-carousel-nav next" aria-label="Next article">›</button>
        <div class="blog-carousel-dots" id="blogDots" role="tablist" aria-label="Article slides"></div>
      </div>
    </div>
  </section>

  <!-- Featured Videos (Platinum) -->
  <!-- <section class="section section-alt" id="videos">
    <div class="container">
      <div class="section-header">
        <div>
          <h2 class="section-title">Featured Promotional Videos</h2>
          <p class="section-subtitle">Short videos from Platinum Plan subscribers showcasing products &amp; services</p>
        </div>
        <span class="plan-badge plan-platinum">Platinum Exclusive</span>
      </div>
      <div class="video-grid">
        <article class="video-card">
          <div class="video-thumb">
            <img src="{{ asset('front/assets/images/movie-1.jpg') }}" alt="Gold jewellery showcase">
            <button type="button" class="video-play-btn" aria-label="Play video">▶</button>
            <span class="video-duration">1:24</span>
          </div>
          <div class="video-info">
            <h3>Shree Gold — Wedding Collection 2026</h3>
            <div class="video-meta">
              <span class="video-author">Shree Gold Jewellers</span>
              <span>Platinum · 2.4K views</span>
            </div>
          </div>
        </article>
        <article class="video-card">
          <div class="video-thumb">
            <img src="{{ asset('front/assets/images/movie-2.jpg') }}" alt="Pharma warehouse tour">
            <button type="button" class="video-play-btn" aria-label="Play video">▶</button>
            <span class="video-duration">0:58</span>
          </div>
          <div class="video-info">
            <h3>Patel Pharma — Warehouse &amp; Supply Chain</h3>
            <div class="video-meta">
              <span class="video-author">Patel Pharma Distributors</span>
              <span>Platinum · 1.8K views</span>
            </div>
          </div>
        </article>
        <article class="video-card">
          <div class="video-thumb">
            <img src="{{ asset('front/assets/images/movie-3.jpg') }}" alt="Commercial property tour">
            <button type="button" class="video-play-btn" aria-label="Play video">▶</button>
            <span class="video-duration">1:45</span>
          </div>
          <div class="video-info">
            <h3>Mehta Real Estate — Office Spaces Tour</h3>
            <div class="video-meta">
              <span class="video-author">Mehta Real Estate</span>
              <span>Platinum · 3.1K views</span>
            </div>
          </div>
        </article>
        <article class="video-card">
          <div class="video-thumb">
            <img src="{{ asset('front/assets/images/movie-4.jpg') }}" alt="Manufacturing facility">
            <button type="button" class="video-play-btn" aria-label="Play video">▶</button>
            <span class="video-duration">2:10</span>
          </div>
          <div class="video-info">
            <h3>Gujarat Steel Works — Factory Overview</h3>
            <div class="video-meta">
              <span class="video-author">Gujarat Steel Works</span>
              <span>Platinum · 956 views</span>
            </div>
          </div>
        </article>
      </div>
    </div>
  </section> -->

  <!-- Subscription Plans -->
  <!-- <section class="section section-alt" id="plans">
    <div class="container">
      <div class="section-header text-center about-section-head">
        <div>
          <h2 class="section-title">Subscription Plans</h2>
          <p class="section-subtitle">Choose the plan that fits your business growth goals</p>
        </div>
      </div>
      <div class="plans-grid">
        <article class="plan-card">
          <div class="plan-card-header">
            <span class="plan-icon">🆓</span>
            <h3>Free Plan</h3>
            <p class="plan-price">Free<span>/15 days</span></p>
          </div>
          <ul class="plan-features">
            <li><span class="plan-check">✓</span> 15 days trial access</li>
            <li><span class="plan-check">✓</span> Basic account registration</li>
            <li><span class="plan-check">✓</span> Explore platform features</li>
            <li><span class="plan-check">✓</span> Company profile (1)</li>
            <li class="plan-muted"><span class="plan-x">✕</span> Services, team &amp; documents</li>
            <li class="plan-muted"><span class="plan-x">✕</span> Promotional listings</li>
          </ul>
          <a href="{{ route('front.register') }}" class="btn btn-outline-primary btn-block">Start Free Trial</a>
        </article>
        <article class="plan-card plan-card-featured">
          <span class="plan-popular">Most Popular</span>
          <div class="plan-card-header">
            <span class="plan-icon">🥇</span>
            <h3>Gold Plan</h3>
            <p class="plan-price">₹3,000<span>/6 months</span></p>
          </div>
          <ul class="plan-features">
            <li><span class="plan-check">✓</span> Valid for 6 months</li>
            <li><span class="plan-check">✓</span> Full company profile</li>
            <li><span class="plan-check">✓</span> All details — add up to 15 times each</li>
            <li><span class="plan-check">✓</span> Services, team, documents &amp; videos</li>
            <li><span class="plan-check">✓</span> Enhanced business visibility</li>
            <li class="plan-muted"><span class="plan-x">✕</span> Unlimited content adds</li>
          </ul>
          <a href="{{ route('front.register') }}" class="btn btn-accent btn-block">Get Gold Plan</a>
        </article>
        <article class="plan-card">
          <div class="plan-card-header">
            <span class="plan-icon">💎</span>
            <h3>Platinum Plan</h3>
            <p class="plan-price">₹4,800<span>/12 months</span></p>
            <p class="plan-discount"><s>₹6,000</s> · 20% discount</p>
          </div>
          <ul class="plan-features">
            <li><span class="plan-check">✓</span> Valid for 12 months</li>
            <li><span class="plan-check">✓</span> Full company profile</li>
            <li><span class="plan-check">✓</span> Unlimited adds for all details</li>
            <li><span class="plan-check">✓</span> Services, team, documents &amp; videos</li>
            <li><span class="plan-check">✓</span> Maximum platform visibility</li>
            <li><span class="plan-check">✓</span> All Gold Plan benefits included</li>
          </ul>
          <a href="{{ route('front.register') }}" class="btn btn-primary btn-block">Go Platinum</a>
        </article>
      </div>
      <div class="plans-comparison">
        <h3>Plan Comparison at a Glance</h3>
        <div class="comparison-table-wrap">
          <table class="comparison-table">
            <thead>
              <tr>
                <th>Feature</th>
                <th>Free</th>
                <th>Gold</th>
                <th>Platinum</th>
              </tr>
            </thead>
            <tbody>
              <tr><td>Plan Duration</td><td>15 days</td><td>6 months</td><td>12 months</td></tr>
              <tr><td>Price</td><td>Free</td><td>₹3,000</td><td>₹4,800 <small>(20% off)</small></td></tr>
              <tr><td>Company Profile</td><td>1</td><td>1</td><td>1</td></tr>
              <tr><td>Add Details Limit</td><td>—</td><td>15 times each</td><td>Unlimited</td></tr>
              <tr><td>Services / Team / Documents</td><td>—</td><td>✓</td><td>✓</td></tr>
              <tr><td>Videos &amp; Promotions</td><td>—</td><td>—</td><td>✓</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section> -->

  <!-- Company Information -->
  <!-- <section class="section company-info-section" id="about-justgoom">
    <div class="container">
      <div class="section-header text-center about-section-head">
        <div>
          <h2 class="section-title">About JustGoom</h2>
          <p class="section-subtitle">Our mission, vision, and the process that drives India's B2B discovery platform</p>
        </div>
      </div>
      <div class="company-info-grid">
        <article class="company-info-card">
          <div class="company-info-icon">🎯</div>
          <h3>JustGoom Objective</h3>
          <p>To connect buyers with verified business profiles across India, enabling MSMEs, suppliers, and service providers to discover opportunities and grow through a trusted digital marketplace.</p>
        </article>
        <article class="company-info-card">
          <div class="company-info-icon">👁️</div>
          <h3>JustGoom Vision</h3>
          <p>To become India's most trusted B2B business discovery platform — where every verified profile represents quality, transparency, and meaningful business connections nationwide.</p>
        </article>
        <article class="company-info-card">
          <div class="company-info-icon">💡</div>
          <h3>JustGoom Purpose</h3>
          <p>Empowering local businesses with digital visibility, subscription-based promotional tools, and resources — from articles and videos to verified profiles — all in one platform.</p>
        </article>
        <article class="company-info-card">
          <div class="company-info-icon">⚙️</div>
          <h3>JustGoom Process</h3>
          <p>Register → Complete Profile → Choose Plan → Get Listed → Publish Content → Receive Inquiries. A simple, structured journey from onboarding to business growth.</p>
        </article>
      </div>
      <div class="company-info-cta">
        <a href="{{ route('front.about') }}" class="btn btn-outline-primary">Learn More About Us</a>
        <a href="{{ route('front.register') }}" class="btn btn-accent">List Your Business Free</a>
      </div>
    </div>
  </section> -->
@endsection

@push('scripts')
@include('front.partials.catalog-data')
<script src="{{ asset('front/assets/js/categories-data.js') }}"></script>
<script src="{{ asset('front/assets/js/categories-render.js') }}"></script>
<script src="{{ asset('front/assets/js/company-profiles.js') }}"></script>
<script src="{{ asset('front/assets/js/home.js') }}"></script>
<script src="{{ asset('front/assets/js/location-selector.js') }}"></script>
<script>
    document.querySelectorAll('.b2b-search-tabs button').forEach(function(btn) {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.b2b-search-tabs button').forEach(function(b) { b.classList.remove('active'); });
        btn.classList.add('active');
        var form = document.querySelector('.b2b-search-form');
        if (btn.dataset.tab === 'post') {
          form.action = @json(route('front.contact'));
          document.getElementById('b2bKeyword').placeholder = 'Describe your requirement...';
        } else {
          form.action = @json(route('front.all-profiles'));
          document.getElementById('b2bKeyword').placeholder = 'Business name, category, or service...';
        }
      });
    });

    document.querySelectorAll('.profile-flow-tabs button').forEach(function(tab) {
      tab.addEventListener('click', function() {
        var flow = tab.dataset.flow;
        document.querySelectorAll('.profile-flow-tabs button').forEach(function(t) {
          t.classList.remove('active');
          t.setAttribute('aria-selected', 'false');
        });
        tab.classList.add('active');
        tab.setAttribute('aria-selected', 'true');
        document.querySelectorAll('.profile-flow-track').forEach(function(panel) {
          var isActive = panel.dataset.flowPanel === flow;
          panel.classList.toggle('active', isActive);
          panel.hidden = !isActive;
        });
      });
    });
  </script>
@endpush
