@extends('front.layouts.app')

@section('title', $profile->company_name . ' — Just Goom')
@section('meta_description', Str::limit(strip_tags($profile->business_desc ?: $profile->tagline ?: $profile->company_name), 160))
@section('body_attrs', 'class="profile-page" data-page="all-profiles"')

@php
  $location = trim(collect([$profile->city, $profile->state, $profile->country])->filter()->implode(', '));
  $fullAddress = trim(collect([$profile->address, $profile->city, $profile->state, $profile->zipcode, $profile->country])->filter()->implode(', '));
  $mapQuery = $fullAddress ?: $location;
  $mapEmbedUrl = $mapQuery
    ? 'https://maps.google.com/maps?q='.rawurlencode($mapQuery).'&z=14&output=embed'
    : null;
  $mapOpenUrl = $mapQuery
    ? 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($mapQuery)
    : null;
  $logoUrl = $profile->logo ? asset($profile->logo) : null;
  $bannerUrl = $bannerUrl ?? \App\Support\ProfileBanner::url($profile, $user);
  $initials = collect(explode(' ', $profile->company_name))
    ->filter()
    ->take(2)
    ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
    ->implode('');
  $websiteUrl = $profile->social_website
    ? (Str::startsWith($profile->social_website, ['http://', 'https://']) ? $profile->social_website : 'https://'.$profile->social_website)
    : null;
  $avatarColors = ['#6366f1', '#ec4899', '#14b8a6', '#f59e0b', '#0ea5e9', '#8b5cf6'];
  $hourDayLabels = [
    'monday' => 'Mon',
    'tuesday' => 'Tue',
    'wednesday' => 'Wed',
    'thursday' => 'Thu',
    'friday' => 'Fri',
    'saturday' => 'Sat',
    'sunday' => 'Sun',
  ];
  $savedHours = is_array($profile->business_hours) ? $profile->business_hours : [];
  $todayKey = strtolower(now()->englishDayOfWeek);
  $todayHours = is_array($savedHours[$todayKey] ?? null) ? $savedHours[$todayKey] : null;
  $todayIsOpen = (bool) ($todayHours['is_open'] ?? false);
  $documentStatus = $profile->documentVerificationStatus();
@endphp

@section('content')
  <section class="profile-hero">
    <div class="profile-hero-bg" style="background-image:url('{{ $bannerUrl }}')"></div>
    <div class="container profile-hero-inner">
      <div class="profile-hero-top">
        <div class="profile-hero-brand">
          <div class="profile-hero-logo">
            @if($logoUrl)
              <img src="{{ $logoUrl }}" alt="{{ $profile->company_name }}">
            @else
              {{ $initials ?: 'JG' }}
            @endif
          </div>
          <div>
            <h1>{{ $profile->company_name }}</h1>
            @if($profile->tagline)
              <p class="profile-tagline">{{ $profile->tagline }}</p>
            @endif
            <p class="profile-category">{{ $user->category->name ?? 'Company' }}</p>
            <div class="profile-meta">
              @if($location)
                <span class="profile-meta-item">
                  <svg class="profile-meta-icon" viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
                  {{ $location }}
                </span>
              @endif
              @if($websiteUrl)
                <a href="{{ $websiteUrl }}" target="_blank" rel="noopener" class="profile-meta-item">
                  <svg class="profile-meta-icon" viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                  {{ parse_url($websiteUrl, PHP_URL_HOST) ?: $profile->social_website }}
                </a>
              @endif
            </div>
          </div>
        </div>
        <div class="profile-hero-badges">
          @if($documentStatus === 'verified')
            <span class="profile-hero-badge profile-hero-badge-verified" title="Documents verified by Just Goom">
              <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
              Verified
            </span>
          @elseif($documentStatus === 'pending')
            <span class="profile-hero-badge profile-hero-badge-pending" title="Documents uploaded, waiting for admin verification">Pending</span>
          @elseif($documentStatus === 'unapproved')
            <span class="profile-hero-badge profile-hero-badge-unapproved" title="Documents were not approved">Unapproved</span>
          @else
            <span class="profile-hero-badge profile-hero-badge-unverified" title="No verified documents yet">Unverified</span>
          @endif
          <span class="profile-hero-badge profile-hero-badge-trending" title="Trending business profile">
            <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6h-6z"/></svg>
            Trending
          </span>
        </div>
      </div>

      <div class="profile-hero-nav">
        <nav class="profile-tabs" id="profileTabs">
          <button type="button" class="active" data-tab="overview">Overview</button>
          <!-- <button type="button" data-tab="activities">Activities</button> -->
          <button type="button" data-tab="services">Services</button>
          <button type="button" data-tab="product">Products</button>
          @php
            $profileProjectSection = \App\Support\ProjectSection::forUser($user);
            $profileProjectsTabLabel = \App\Support\ProjectSection::pluralLabel($profileProjectSection);
          @endphp
          <button type="button" data-tab="projects">{{ $profileProjectsTabLabel }}</button>
          <button type="button" data-tab="documents">Documents</button>
          <button type="button" data-tab="videos">Videos</button>
          <button type="button" data-tab="blog">My Blog</button>
          <button type="button" data-tab="offers">My Advertisement</button>
        </nav>
      </div>
    </div>
  </section>

  <div class="container profile-body">
    <aside class="profile-sidebar">
      @if($isOwner)
        <div class="profile-card profile-progress-card is-{{ $completionLevel }}">
          <h3>Complete Your Profile</h3>
          <div class="profile-progress-bar">
            <div class="profile-progress-fill" style="width:{{ $completionPercent }}%"></div>
          </div>
          <span class="profile-progress-label">{{ $completionPercent }}%</span>
        </div>
      @endif

      <div class="profile-card">
        <h3>Info</h3>
        <ul class="profile-info-list">
          <li><span>Full Name :</span><strong>{{ $profile->company_name }}</strong></li>
          @if($profile->phone)
            <li><span>Mobile :</span><strong>+91 {{ $profile->phone }}</strong></li>
          @endif
          @if($profile->email)
            <li><span>E-mail :</span><strong>{{ $profile->email }}</strong></li>
          @endif
        </ul>
      </div>
      @if($mapQuery)
        <div class="profile-card profile-location-card">
          <h3>Location</h3>
          <p class="profile-sidebar-address">
            <svg class="profile-meta-icon" viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
            <span>{{ $mapQuery }}</span>
          </p>
          <div class="profile-map-embed">
            <iframe
              title="Business location map"
              src="{{ $mapEmbedUrl }}"
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              allowfullscreen
            ></iframe>
          </div>
          <a href="{{ $mapOpenUrl }}" class="btn btn-outline btn-sm btn-block" target="_blank" rel="noopener">Open in Maps</a>
        </div>
      @endif
      <div class="profile-card">
        <h3>Category</h3>
        <ul class="profile-info-list">
          <li>
            <span>Category :</span>
            <strong>{{ $user->category->name ?? '-' }}</strong>
          </li>
          <li>
            <span>Sub Category :</span>
            @php $subCategories = $user->subCategories(); @endphp
            @if($subCategories->isNotEmpty())
              <div class="profile-subcat-tags">
                @foreach($subCategories as $subCategory)
                  <span class="profile-subcat-tag">{{ $subCategory->name }}</span>
                @endforeach
              </div>
            @else
              <strong>-</strong>
            @endif
          </li>
        </ul>
      </div>
      <div
        class="profile-card profile-share-qr-card"
        id="profileQrFlyer"
        data-company="{{ $profile->company_name }}"
        data-logo="{{ $logoUrl }}"
        data-initials="{{ $initials ?: 'JG' }}"
        data-category="{{ $user->category->name ?? 'Company Profile' }}"
        data-profile-url="{{ $profileUrl }}"
        data-qr-url="{{ $qrDownloadUrl }}"
        data-jg-logo="{{ $justgoomLogoUrl }}"
      >
        <h3>Share Profile</h3>
        <div class="profile-qr-wrap">
          <div id="profileQrMount" role="img" aria-label="Profile QR Code"></div>
        </div>
        <p class="profile-qr-note">Scan to view this profile</p>
        <button type="button" class="btn btn-share-profile btn-sm btn-block" id="shareProfileBtn" data-url="{{ $profileUrl }}">
          <svg class="btn-share-icon" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
            <path fill="currentColor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
          </svg>
          <span class="btn-share-label">Share Profile</span>
        </button>
        <button type="button" class="btn btn-outline btn-sm btn-block" id="downloadProfileQrBtn">Download QR</button>
      </div>
    </aside>

    <main class="profile-content">
      <div class="profile-tab-pane active" data-pane="overview">
        <div class="profile-card profile-about-card">
          <h3>About</h3>
          <div class="profile-about-text">
            @if($profile->tagline && $profile->business_desc)
              <p class="profile-about-tagline">{{ $profile->tagline }}</p>
            @endif
            @if($profile->business_desc)
              @foreach(preg_split("/\n\s*\n/", trim($profile->business_desc)) as $para)
                @if(trim($para) !== '')
                  <p>{{ trim($para) }}</p>
                @endif
              @endforeach
            @elseif($profile->tagline)
              <p>{{ $profile->tagline }}</p>
            @else
              <p>No business description added yet.</p>
            @endif
          </div>
          <div class="profile-quick-info">
            <div class="quick-info-item">
              <span class="quick-info-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/></svg>
              </span>
              <div>
                <span class="quick-info-label">Company Type</span>
                <strong>Private Limited</strong>
              </div>
            </div>
            <div class="quick-info-item">
              <span class="quick-info-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
              </span>
              <div>
                <span class="quick-info-label">Website</span>
                <strong>{{ $profile->social_website ?: '-' }}</strong>
              </div>
            </div>
          </div>

          @php
            $hasPortfolio = $profile->social_website || $profile->social_facebook || $profile->social_twitter || $profile->social_linkedin;
            $facebookUrl = $profile->social_facebook
              ? (Str::startsWith($profile->social_facebook, ['http://', 'https://']) ? $profile->social_facebook : 'https://'.$profile->social_facebook)
              : null;
            $twitterUrl = $profile->social_twitter
              ? (Str::startsWith($profile->social_twitter, ['http://', 'https://']) ? $profile->social_twitter : 'https://'.$profile->social_twitter)
              : null;
            $linkedinUrl = $profile->social_linkedin
              ? (Str::startsWith($profile->social_linkedin, ['http://', 'https://']) ? $profile->social_linkedin : 'https://'.$profile->social_linkedin)
              : null;
          @endphp
          @if($savedHours !== [] || $hasPortfolio)
            <div class="profile-about-extras">
              @if($savedHours !== [])
                <div class="profile-trade-card profile-about-hours">
                  <div class="profile-trade-head">
                    <h4 class="profile-about-extra-title">Business Hours</h4>
                    <span class="profile-trade-status {{ $todayIsOpen ? 'is-open' : 'is-closed' }}">{{ $todayIsOpen ? 'Open today' : 'Closed today' }}</span>
                  </div>
                  <ul class="profile-hours-list business-hours-grid">
                    @foreach($hourDayLabels as $dayKey => $dayLabel)
                      @php
                        $dayHours = is_array($savedHours[$dayKey] ?? null) ? $savedHours[$dayKey] : [];
                        $isOpen = (bool) ($dayHours['is_open'] ?? false);
                        $isToday = $dayKey === $todayKey;
                      @endphp
                      <li class="{{ $isToday ? 'hours-today' : '' }} {{ $isOpen ? 'is-open' : 'is-closed' }}">
                        <span class="hours-pip" aria-hidden="true"></span>
                        <span class="hours-day">{{ $dayLabel }}</span>
                        @if($isOpen)
                          <span class="hours-time">{{ $dayHours['open'] ?? '' }} – {{ $dayHours['close'] ?? '' }}</span>
                        @else
                          <span class="hours-closed">Closed</span>
                        @endif
                      </li>
                    @endforeach
                  </ul>
                </div>
              @endif
              @if($hasPortfolio)
                <div class="profile-trade-card profile-about-portfolio">
                  <div class="profile-trade-head">
                    <h4 class="profile-about-extra-title">Portfolio</h4>
                  </div>
                  <div class="profile-portfolio">
                    @if($websiteUrl)
                      <a href="{{ $websiteUrl }}" class="portfolio-link" target="_blank" rel="noopener">
                        <span class="portfolio-icon web" aria-hidden="true">
                          <svg viewBox="0 0 24 24" width="16" height="16"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                        </span>
                        <span class="portfolio-link-text">
                          <strong>Website</strong>
                          <span>{{ parse_url($websiteUrl, PHP_URL_HOST) ?: $profile->social_website }}</span>
                        </span>
                      </a>
                    @endif
                    @if($facebookUrl)
                      <a href="{{ $facebookUrl }}" class="portfolio-link" target="_blank" rel="noopener">
                        <span class="portfolio-icon github">f</span>
                        <span class="portfolio-link-text">
                          <strong>Facebook</strong>
                          <span>View page</span>
                        </span>
                      </a>
                    @endif
                    @if($twitterUrl)
                      <a href="{{ $twitterUrl }}" class="portfolio-link" target="_blank" rel="noopener">
                        <span class="portfolio-icon dribbble">X</span>
                        <span class="portfolio-link-text">
                          <strong>Twitter / X</strong>
                          <span>View profile</span>
                        </span>
                      </a>
                    @endif
                    @if($linkedinUrl)
                      <a href="{{ $linkedinUrl }}" class="portfolio-link" target="_blank" rel="noopener">
                        <span class="portfolio-icon behance">in</span>
                        <span class="portfolio-link-text">
                          <strong>LinkedIn</strong>
                          <span>View company</span>
                        </span>
                      </a>
                    @endif
                  </div>
                </div>
              @endif
            </div>
          @endif
        </div>

        <div class="profile-card profile-team-card">
          <div class="profile-team-header">
            <h3>Team <span class="profile-section-count">({{ $teams->count() }})</span></h3>
          </div>
          @if($teams->isNotEmpty())
            <div class="profile-items-scroll profile-items-scroll--grid {{ $teams->count() > 16 ? 'is-scrollable' : '' }}">
              <div class="profile-team-grid">
                @foreach($teams as $member)
                  @php
                    $memberInitials = collect(explode(' ', $member->name))
                      ->filter()
                      ->take(2)
                      ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                      ->implode('');
                    $avatarColor = $avatarColors[crc32($member->name) % count($avatarColors)];
                    $memberImage = $member->image ? asset($member->image) : '';
                  @endphp
                  <article class="team-member-card">
                    @if($member->image)
                      <img src="{{ $memberImage }}" alt="{{ $member->name }}" class="team-avatar-img">
                    @else
                      <div class="team-avatar" style="background:{{ $avatarColor }}">{{ $memberInitials }}</div>
                    @endif
                    <h4 title="{{ $member->name }}">{{ $member->name }}</h4>
                    @if($member->designation)
                      <p class="team-role" title="{{ $member->designation }}">{{ $member->designation }}</p>
                    @endif
                    @if($member->department)
                      <p class="team-department">
                        <svg class="team-department-icon" viewBox="0 0 24 24" width="12" height="12" aria-hidden="true"><path fill="currentColor" d="M20 18c1.1 0 1.99-.9 1.99-2L22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2H0v2h24v-2h-4zM4 6h16v10H4V6z"/></svg>
                        {{ $member->department }}
                      </p>
                    @endif
                    <button
                      type="button"
                      class="btn btn-primary btn-block btn-sm js-team-view-profile"
                      data-name="{{ $member->name }}"
                      data-designation="{{ $member->designation }}"
                      data-phone="{{ $member->phone }}"
                      data-email="{{ $member->email }}"
                      data-department="{{ $member->department }}"
                      data-info="{{ $member->short_info }}"
                      data-image="{{ $memberImage }}"
                      data-initials="{{ $memberInitials }}"
                      data-color="{{ $avatarColor }}"
                    >View Profile</button>
                  </article>
                @endforeach
              </div>
            </div>
          @else
            <p class="profile-empty-note">No team members added yet.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="activities">
        <div class="profile-card">
          <h3>Recent Activities</h3>
          @if($activities->isNotEmpty())
            <ul class="profile-activity-list">
              @foreach($activities as $activity)
                <li>
                  <span class="activity-dot"></span>
                  {{ $activity['text'] }} — <time>{{ $activity['time'] }}</time>
                </li>
              @endforeach
            </ul>
          @else
            <p class="profile-empty-note">No recent activities.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="services">
        <div class="profile-card">
          <h3>Services <span class="profile-section-count">({{ $services->count() }})</span></h3>
          @if($services->isNotEmpty())
            <div class="profile-items-scroll profile-items-scroll--list {{ $services->count() > 10 ? 'is-scrollable' : '' }}">
              <div class="profile-services-list">
                @foreach($services as $service)
                  <article class="profile-service-row">
                    <div class="profile-service-row-media">
                      @if($service->product_image)
                        <img src="{{ asset($service->product_image) }}" alt="{{ $service->product_name }}">
                      @else
                        <div class="profile-service-row-placeholder" aria-hidden="true">
                          <svg viewBox="0 0 24 24" width="28" height="28"><path fill="currentColor" d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/></svg>
                        </div>
                      @endif
                    </div>
                    <div class="profile-service-row-body">
                      <div class="profile-service-row-top">
                        <span class="profile-item-badge profile-item-badge--service">Service</span>
                        @if($service->formattedPrice())
                          <span class="profile-service-row-price">{{ $service->formattedPrice() }}</span>
                        @endif
                      </div>
                      <h4>{{ $service->product_name }}</h4>
                      @if($service->product_desc)
                        <p>{{ Str::limit($service->product_desc, 140) }}</p>
                      @endif
                    </div>
                  </article>
                @endforeach
              </div>
            </div>
          @else
            <p class="profile-empty-note">No services listed yet.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="product">
        <div class="profile-card">
          <h3>Products <span class="profile-section-count">({{ $products->count() }})</span></h3>
          @if($products->isNotEmpty())
            <div class="profile-items-scroll profile-items-scroll--cards {{ $products->count() > 12 ? 'is-scrollable' : '' }}">
              <div class="profile-products-grid">
                @foreach($products as $product)
                  <article class="profile-product-card">
                    <div class="profile-product-card-media">
                      @if($product->product_image)
                        <img src="{{ asset($product->product_image) }}" alt="{{ $product->product_name }}">
                      @else
                        <div class="profile-product-card-placeholder" aria-hidden="true">
                          <svg viewBox="0 0 24 24" width="40" height="40"><path fill="currentColor" d="M20 6H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 13H4V8h16v11zM8 10h2v2H8v-2zm0 4h2v2H8v-2zm4-4h2v2h-2v-2zm0 4h2v2h-2v-2zm4-4h2v2h-2v-2zm0 4h2v2h-2v-2z"/></svg>
                        </div>
                      @endif
                      <span class="profile-item-badge profile-item-badge--product">Product</span>
                    </div>
                    <div class="profile-product-card-body">
                      <h4>{{ $product->product_name }}</h4>
                      @if($product->formattedPrice())
                        <div class="profile-product-price">{{ $product->formattedPrice() }}</div>
                      @endif
                      @if($product->product_desc)
                        <p>{{ Str::limit($product->product_desc, 80) }}</p>
                      @endif
                    </div>
                  </article>
                @endforeach
              </div>
            </div>
          @else
            <p class="profile-empty-note">No products listed yet.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="projects">
        <div class="profile-card">
          @php
            $profileProjectSection = $profileProjectSection ?? \App\Support\ProjectSection::forUser($user);
            $isRealEstateCategory = $profileProjectSection === \App\Support\ProjectSection::REAL_ESTATE;
            $isEngineeringCategory = $profileProjectSection === \App\Support\ProjectSection::ENGINEERING;
            $isEcommerceCategory = $profileProjectSection === \App\Support\ProjectSection::ECOMMERCE;
            $isGalleryListingCategory = $isRealEstateCategory || $isEngineeringCategory;
          @endphp

          @if($isGalleryListingCategory)
            <h3>{{ $profileProjectsTabLabel ?? ($isEngineeringCategory ? 'Engineering Listings' : 'Property Listings') }} <span class="profile-section-count">({{ $projects->count() }})</span></h3>
            @if($projects->isNotEmpty())
              <div class="profile-project-listings listing-cards {{ $projects->count() > 8 ? 'is-scrollable' : '' }}">
                @foreach($projects as $project)
                  @php
                    $listingImages = $project->mediaImages();
                    $coverImage = $project->coverImage();
                    $listingLocation = $project->metaValue('location') ?: $location;
                    $photoCount = count($listingImages) ?: (int) ($project->metaValue('photo_count') ?: 0);
                    $tagList = $isEngineeringCategory ? $project->featuresList() : $project->amenitiesList();
                    $priceNote = $isEngineeringCategory
                      ? $project->metaValue('price_note')
                      : $project->metaValue('emi');
                    $actionUrl = $project->external_url ?: null;
                    $actionLabel = $actionUrl ? 'View Details' : null;
                    $postedLabel = trim(($profile->company_name ?: 'Owner').' · '.($project->created_at?->format('M j, Y') ?? ''));
                  @endphp
                  <article class="listing-card listing-card--v2 profile-project-listing {{ $isEngineeringCategory ? 'listing-card--engineering' : 'listing-card--property' }}">
                    <div class="listing-card-img">
                      @if($coverImage)
                        <button
                          type="button"
                          class="listing-card-img-btn js-listing-gallery-open"
                          data-title="{{ e($project->title) }}"
                          data-images='@json(collect($listingImages)->map(fn ($img) => asset($img))->values())'
                          aria-label="View photos of {{ $project->title }}"
                        >
                          <img src="{{ asset($coverImage) }}" alt="{{ $project->title }}">
                        </button>
                      @else
                        <div class="profile-project-listing-placeholder" aria-hidden="true">
                          <svg viewBox="0 0 24 24" width="48" height="48"><path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                        </div>
                      @endif
                      <div class="listing-card-img-overlay" aria-hidden="true"></div>
                      @if($isEngineeringCategory)
                        <span class="listing-type-badge">Engineering</span>
                      @elseif($project->metaValue('sale_type'))
                        <span class="listing-type-badge">{{ $project->metaValue('sale_type') }}</span>
                      @else
                        <span class="listing-type-badge">Property</span>
                      @endif
                      @if($photoCount > 0 && count($listingImages) > 0)
                        <button
                          type="button"
                          class="photo-count js-listing-gallery-open"
                          data-title="{{ e($project->title) }}"
                          data-images='@json(collect($listingImages)->map(fn ($img) => asset($img))->values())'
                          aria-label="View {{ $photoCount }} photos"
                        >
                          <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                          {{ $photoCount }} Photos
                        </button>
                      @endif
                    </div>
                    <div class="listing-card-body">
                      <div class="listing-card-top">
                        <div class="listing-card-heading">
                          <h2>{{ $project->title }}</h2>
                          @if($listingLocation)
                            <p class="listing-location">
                              <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                              {{ $listingLocation }}
                            </p>
                          @endif
                        </div>
                        <div class="listing-price">
                          <div class="amount">{{ $project->formattedPrice() ?: '—' }}</div>
                          @if($priceNote)
                            <div class="emi">{{ $priceNote }}</div>
                          @endif
                        </div>
                      </div>

                      <div class="listing-specs listing-specs--tiles">
                        @if($isEngineeringCategory)
                          @if($project->metaValue('service_type'))
                            <div class="spec-tile">
                              <span class="spec-tile-label">Service</span>
                              <strong>{{ $project->metaValue('service_type') }}</strong>
                            </div>
                          @endif
                          @if($project->metaValue('domain'))
                            <div class="spec-tile">
                              <span class="spec-tile-label">Domain</span>
                              <strong>{{ $project->metaValue('domain') }}</strong>
                            </div>
                          @endif
                          @if($project->metaValue('lead_time'))
                            <div class="spec-tile">
                              <span class="spec-tile-label">Lead Time</span>
                              <strong>{{ $project->metaValue('lead_time') }}</strong>
                            </div>
                          @endif
                          @if($project->metaValue('capacity'))
                            <div class="spec-tile">
                              <span class="spec-tile-label">Capacity</span>
                              <strong>{{ $project->metaValue('capacity') }}</strong>
                            </div>
                          @endif
                        @else
                          @if($project->metaValue('config'))
                            <div class="spec-tile">
                              <span class="spec-tile-label">Config</span>
                              <strong>{{ $project->metaValue('config') }}</strong>
                            </div>
                          @endif
                          @if($project->metaValue('sale_type'))
                            <div class="spec-tile">
                              <span class="spec-tile-label">Sale Type</span>
                              <strong>{{ $project->metaValue('sale_type') }}</strong>
                            </div>
                          @endif
                          @if($project->metaValue('possession'))
                            <div class="spec-tile">
                              <span class="spec-tile-label">Possession</span>
                              <strong>{{ $project->metaValue('possession') }}</strong>
                            </div>
                          @endif
                          @if($project->metaValue('parking'))
                            <div class="spec-tile">
                              <span class="spec-tile-label">Parking</span>
                              <strong>{{ $project->metaValue('parking') }}</strong>
                            </div>
                          @endif
                        @endif
                      </div>

                      @if($project->description)
                        <p class="listing-desc">{{ Str::limit(strip_tags($project->description), 160) }}</p>
                      @endif
                      @if(count($tagList))
                        <div class="listing-amenities">
                          @foreach(array_slice($tagList, 0, 3) as $tag)
                            <span class="amenity-tag">{{ $tag }}</span>
                          @endforeach
                          @if(count($tagList) > 3)
                            <span class="amenity-tag amenity-tag--more">+{{ count($tagList) - 3 }} more</span>
                          @endif
                        </div>
                      @endif
                      <div class="listing-card-footer">
                        <span class="listing-posted">by {{ $postedLabel }}</span>
                        <div class="listing-actions">
                          @if($actionUrl)
                            <a href="{{ $actionUrl }}" class="btn btn-primary btn-sm" target="_blank" rel="noopener">{{ $actionLabel }}</a>
                          @endif
                          @if($profile->phone)
                            <a href="tel:+91{{ preg_replace('/\D+/', '', $profile->phone) }}" class="btn btn-outline btn-sm">Contact</a>
                          @endif
                        </div>
                      </div>
                    </div>
                  </article>
                @endforeach
              </div>
            @else
              <p class="profile-empty-note">{{ $isEngineeringCategory ? 'No engineering listings published yet.' : 'No property listings published yet.' }}</p>
            @endif
          @elseif($isEcommerceCategory)
            <h3>{{ $profileProjectsTabLabel ?? 'Products' }} <span class="profile-section-count">({{ $projects->count() }})</span></h3>
            @if($projects->isNotEmpty())
              <div class="profile-items-scroll profile-items-scroll--cards {{ $projects->count() > 16 ? 'is-scrollable' : '' }}">
                <div class="profile-services-grid profile-services-cards">
                  @foreach($projects as $project)
                    <article class="profile-service-card">
                      @if($project->thumbnail)
                        <img src="{{ asset($project->thumbnail) }}" alt="{{ $project->title }}">
                      @else
                        <div class="profile-service-card-placeholder" aria-hidden="true">
                          <svg viewBox="0 0 24 24" width="36" height="36"><path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                        </div>
                      @endif
                      <div class="profile-service-card-body">
                        <h4>{{ $project->title }}</h4>
                        @if($project->formattedPrice())
                          <span class="profile-project-type">{{ $project->formattedPrice() }}</span>
                        @endif
                        @if($project->description)
                          <p>{{ Str::limit(strip_tags($project->description), 90) }}</p>
                        @endif
                        @if($project->external_url)
                          <a href="{{ $project->external_url }}" class="profile-inline-link" target="_blank" rel="noopener">View Product</a>
                        @endif
                      </div>
                    </article>
                  @endforeach
                </div>
              </div>
            @else
              <p class="profile-empty-note">No products published yet.</p>
            @endif
          @else
            <h3>Projects <span class="profile-section-count">({{ $projects->count() }})</span></h3>
            @if($projects->isNotEmpty())
              <div class="profile-items-scroll profile-items-scroll--cards {{ $projects->count() > 16 ? 'is-scrollable' : '' }}">
                <div class="profile-services-grid profile-services-cards">
                  @foreach($projects as $project)
                    @php
                      $typeLabel = $project->typeLabel();
                      $actionUrl = null;
                      $actionLabel = null;
                      $isDownload = false;
                      if ($project->type === 'document' && $project->file_path) {
                        $actionUrl = asset($project->file_path);
                        $actionLabel = 'Download';
                        $isDownload = true;
                      } elseif ($project->type === 'video' && $project->file_path) {
                        $actionUrl = asset($project->file_path);
                        $actionLabel = 'Watch';
                      } elseif ($project->type === 'link' && $project->external_url) {
                        $actionUrl = $project->external_url;
                        $actionLabel = 'Open Link';
                      } elseif ($project->external_url) {
                        $actionUrl = $project->external_url;
                        $actionLabel = 'Open Link';
                      } elseif ($project->file_path) {
                        $actionUrl = asset($project->file_path);
                        $actionLabel = 'View';
                      }
                    @endphp
                    <article class="profile-service-card">
                      @if($project->thumbnail)
                        <img src="{{ asset($project->thumbnail) }}" alt="{{ $project->title }}">
                      @else
                        <div class="profile-service-card-placeholder" aria-hidden="true">
                          <svg viewBox="0 0 24 24" width="36" height="36"><path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                        </div>
                      @endif
                      <div class="profile-service-card-body">
                        <h4>{{ $project->title }}</h4>
                        @if($project->type)
                          <span class="profile-project-type">{{ $typeLabel }}</span>
                        @endif
                        @if($project->description)
                          <p>{{ Str::limit(strip_tags($project->description), 90) }}</p>
                        @endif
                        @if($actionUrl)
                          <a
                            href="{{ $actionUrl }}"
                            class="profile-inline-link"
                            target="_blank"
                            rel="noopener"
                            @if($isDownload) download @endif
                          >{{ $actionLabel }}</a>
                        @endif
                      </div>
                    </article>
                  @endforeach
                </div>
              </div>
            @else
              <p class="profile-empty-note">No projects published yet.</p>
            @endif
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="documents">
        <div class="profile-card">
          <h3>Documents <span class="profile-section-count">({{ $documents->count() }})</span></h3>
          @if($documents->isNotEmpty())
            <div class="profile-items-scroll {{ $documents->count() > 16 ? 'is-scrollable' : '' }}">
              <ul class="profile-doc-list">
                @foreach($documents as $document)
                  @php $docType = $document->file_type ?: 'pdf'; @endphp
                  <li class="profile-doc-item profile-doc-item--{{ $docType }}">
                    <div class="profile-doc-icon" aria-hidden="true">
                      @if($docType === 'excel')
                        <svg viewBox="0 0 48 48" width="40" height="40" focusable="false">
                          <path fill="#1D6F42" d="M10 4h20l10 10v30a4 4 0 0 1-4 4H10a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4z"/>
                          <path fill="#ffffff" opacity=".25" d="M30 4v10h10"/>
                          <text x="24" y="32" text-anchor="middle" fill="#fff" font-size="11" font-weight="700" font-family="Arial,sans-serif">XLS</text>
                        </svg>
                      @elseif($docType === 'word')
                        <svg viewBox="0 0 48 48" width="40" height="40" focusable="false">
                          <path fill="#2B579A" d="M10 4h20l10 10v30a4 4 0 0 1-4 4H10a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4z"/>
                          <path fill="#ffffff" opacity=".25" d="M30 4v10h10"/>
                          <text x="24" y="32" text-anchor="middle" fill="#fff" font-size="14" font-weight="700" font-family="Arial,sans-serif">W</text>
                        </svg>
                      @elseif($docType === 'image')
                        <svg viewBox="0 0 48 48" width="40" height="40" focusable="false">
                          <path fill="#0D8ABC" d="M10 4h20l10 10v30a4 4 0 0 1-4 4H10a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4z"/>
                          <path fill="#ffffff" opacity=".25" d="M30 4v10h10"/>
                          <rect x="12" y="18" width="24" height="18" rx="2" fill="#fff"/>
                          <circle cx="18" cy="24" r="2.5" fill="#0D8ABC"/>
                          <path fill="#0D8ABC" d="M14 34l6-7 4 5 3-4 7 6H14z"/>
                        </svg>
                      @else
                        <svg viewBox="0 0 48 48" width="40" height="40" focusable="false">
                          <path fill="#E53935" d="M10 4h20l10 10v30a4 4 0 0 1-4 4H10a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4z"/>
                          <path fill="#ffffff" opacity=".25" d="M30 4v10h10"/>
                          <text x="24" y="32" text-anchor="middle" fill="#fff" font-size="11" font-weight="700" font-family="Arial,sans-serif">PDF</text>
                        </svg>
                      @endif
                    </div>
                    <div class="profile-doc-body">
                      <strong>{{ $document->title }}</strong>
                      <small>{{ $document->fileTypeLabel() }} &middot; {{ $document->created_at?->format('M j, Y') }}</small>
                      <a href="{{ asset($document->attachment) }}" target="_blank" rel="noopener" download>Download</a>
                    </div>
                  </li>
                @endforeach
              </ul>
            </div>
          @else
            <p class="profile-empty-note">No documents available.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="videos">
        <div class="profile-card">
          <h3>Videos <span class="profile-section-count">({{ $videos->count() }})</span></h3>
          @if($videos->isNotEmpty())
            <div class="profile-items-scroll profile-items-scroll--cards {{ $videos->count() > 9 ? 'is-scrollable' : '' }}">
              <div class="profile-videos-grid">
                @foreach($videos as $video)
                  @php
                    $rawLink = trim((string) $video->link);
                    $isExternal = str_starts_with($rawLink, 'http');
                    $embedUrl = null;
                    $openUrl = null;
                    $uploadedUrl = null;
                    $sourceLabel = 'Uploaded video';
                    $thumbUrl = null;
                    $youtubeId = null;

                    if (! empty($video->thumbnail)) {
                      $thumbUrl = asset($video->thumbnail);
                    }

                    if ($isExternal) {
                      if (preg_match('~(?:youtube\.com/watch\?v=|youtube\.com/embed/|youtu\.be/)([A-Za-z0-9_-]{6,})~', $rawLink, $m)) {
                        $youtubeId = $m[1];
                        $embedUrl = 'https://www.youtube.com/embed/' . $youtubeId;
                        $sourceLabel = 'YouTube';
                      } elseif (preg_match('~youtube\.com/shorts/([A-Za-z0-9_-]{6,})~', $rawLink, $m)) {
                        $youtubeId = $m[1];
                        $embedUrl = 'https://www.youtube.com/embed/' . $youtubeId;
                        $sourceLabel = 'YouTube Shorts';
                      } elseif (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $rawLink, $m)) {
                        $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
                        $sourceLabel = 'Vimeo';
                      } elseif (preg_match('~instagram\.com/(reel|reels|p)/([A-Za-z0-9_-]+)~', $rawLink, $m)) {
                        $igType = $m[1] === 'p' ? 'p' : 'reel';
                        $openUrl = 'https://www.instagram.com/' . $igType . '/' . $m[2] . '/';
                        $sourceLabel = $igType === 'p' ? 'Instagram Post' : 'Instagram Reel';
                      } elseif (preg_match('~(?:facebook\.com|fb\.watch).*(?:reel|videos?)/|/reel/~i', $rawLink)) {
                        $openUrl = $rawLink;
                        $sourceLabel = 'Facebook Reel';
                      } elseif (preg_match('~(?:tiktok\.com/|vm\.tiktok\.com/)~i', $rawLink)) {
                        $openUrl = $rawLink;
                        $sourceLabel = 'TikTok';
                      } elseif (preg_match('~(?:reel|reels)~i', $rawLink)) {
                        $openUrl = $rawLink;
                        $sourceLabel = 'Reel';
                      } else {
                        $openUrl = $rawLink;
                        $sourceLabel = 'External video';
                      }
                    } else {
                      $uploadedUrl = asset($rawLink);
                    }

                    if (! $thumbUrl && $youtubeId) {
                      $thumbUrl = 'https://img.youtube.com/vi/' . $youtubeId . '/hqdefault.jpg';
                    }

                    if (! $thumbUrl && $openUrl) {
                      $fallbackThumbs = [
                        asset('front/assets/images/movie-1.jpg'),
                        asset('front/assets/images/movie-2.jpg'),
                        asset('front/assets/images/movie-3.jpg'),
                        asset('front/assets/images/movie-4.jpg'),
                        asset('front/assets/images/movie-5.jpg'),
                        asset('front/assets/images/movie-6.jpg'),
                      ];
                      $thumbUrl = $fallbackThumbs[$loop->index % count($fallbackThumbs)];
                    }
                  @endphp
                  <article class="profile-video-card">
                    <div class="profile-video-frame">
                      @if($embedUrl)
                        <iframe
                          src="{{ $embedUrl }}"
                          title="{{ $video->title }}"
                          loading="lazy"
                          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                          allowfullscreen
                          referrerpolicy="strict-origin-when-cross-origin"
                        ></iframe>
                      @elseif($openUrl)
                        <a
                          href="{{ $openUrl }}"
                          class="profile-video-open"
                          target="_blank"
                          rel="noopener noreferrer"
                          aria-label="Open {{ $video->title }}"
                        >
                          @if($thumbUrl)
                            <img src="{{ $thumbUrl }}" alt="{{ $video->title }}" class="profile-video-thumb" loading="lazy">
                          @endif
                          <span class="profile-video-open-play" aria-hidden="true">▶</span>
                          <span class="profile-video-open-text">Open {{ $sourceLabel }}</span>
                        </a>
                      @else
                        @if($thumbUrl)
                          <a href="{{ $uploadedUrl }}" class="profile-video-open" target="_blank" rel="noopener noreferrer" aria-label="Play {{ $video->title }}">
                            <img src="{{ $thumbUrl }}" alt="{{ $video->title }}" class="profile-video-thumb" loading="lazy">
                            <span class="profile-video-open-play" aria-hidden="true">▶</span>
                          </a>
                        @else
                          <iframe
                            src="{{ $uploadedUrl }}"
                            title="{{ $video->title }}"
                            loading="lazy"
                            allow="autoplay; fullscreen; encrypted-media"
                            allowfullscreen
                          ></iframe>
                        @endif
                      @endif
                    </div>
                    <div class="profile-video-body">
                      <strong>{{ $video->title }}</strong>
                      <small>{{ $sourceLabel }} &middot; {{ \Carbon\Carbon::parse($video->created_at)->format('M j, Y') }}</small>
                      @if($openUrl)
                        <a href="{{ $openUrl }}" target="_blank" rel="noopener noreferrer" class="profile-inline-link">Open link</a>
                      @endif
                    </div>
                  </article>
                @endforeach
              </div>
            </div>
          @else
            <p class="profile-empty-note">No videos available.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="blog">
        <div class="profile-card">
          <h3>My Blog <span class="profile-section-count">({{ $articles->count() }})</span></h3>
          @if($articles->isNotEmpty())
            @php
              $blogFallbackImages = [
                asset('front/assets/images/blog-1.jpg'),
                asset('front/assets/images/blog-2.jpg'),
                asset('front/assets/images/blog-3.jpg'),
              ];
              $blogAuthorName = $profile->company_name ?: 'JustGoom Member';
              $blogAuthorInitials = $initials ?: 'JG';
              $blogTag = $user->category->name ?? 'Business';
            @endphp
            <div class="profile-items-scroll profile-items-scroll--cards {{ $articles->count() > 6 ? 'is-scrollable' : '' }}">
              <div class="blog-grid profile-blog-grid">
                @foreach($articles as $index => $article)
                  @php
                    $published = $article->published_at ?? $article->created_at;
                    $excerpt = \Illuminate\Support\Str::limit(strip_tags($article->body), 110);
                    $readMins = max(1, (int) ceil(str_word_count(strip_tags($article->body)) / 200));
                    $blogImage = $article->featured_image
                      ? asset($article->featured_image)
                      : $blogFallbackImages[$index % count($blogFallbackImages)];
                  @endphp
                  <a href="{{ route('front.articles.show', $article->slug) }}" class="blog-card blog-card-link">
                    <div class="blog-thumb">
                      <img src="{{ $blogImage }}" alt="{{ $article->title }}" loading="lazy">
                    </div>
                    <div class="blog-body">
                      <div class="article-author-bar">
                        <span class="blog-author-avatar">{{ $blogAuthorInitials }}</span>
                        <div>
                          <strong>{{ $blogAuthorName }}</strong>
                        </div>
                      </div>
                      <span class="blog-tag">{{ $blogTag }}</span>
                      <h3>{{ $article->title }}</h3>
                      <p>{{ $excerpt }}</p>
                      <div class="blog-footer">
                        <span>{{ $published?->format('M j, Y') }}</span>
                        <span>{{ $readMins }} min read</span>
                      </div>
                    </div>
                  </a>
                @endforeach
              </div>
            </div>
          @else
            <p class="profile-empty-note">No blog posts published yet.</p>
          @endif
        </div>
      </div>

      <div class="profile-tab-pane" data-pane="offers" id="offers">
        <div class="profile-card">
          <h3>My Advertisement <span class="profile-section-count">({{ $offers->count() }})</span></h3>
          @if($offers->isNotEmpty())
            <div class="profile-items-scroll profile-items-scroll--cards {{ $offers->count() > 8 ? 'is-scrollable' : '' }}">
              <div class="profile-offers-grid">
                @foreach($offers as $offer)
                  @php
                    $offerImage = $offer->banner_image
                      ? asset($offer->banner_image)
                      : asset('front/assets/images/cat-business.jpg');
                    $offerUrl = $offer->link_url ?: '#';
                    $hasOfferLink = filled($offer->link_url);
                  @endphp
                  <article class="profile-offer-card">
                    <div class="profile-offer-thumb">
                      <img src="{{ $offerImage }}" alt="{{ $offer->title }}" loading="lazy">
                    </div>
                    <div class="profile-offer-body">
                      <h4>{{ $offer->title }}</h4>
                      @if($offer->description)
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($offer->description), 100) }}</p>
                      @endif
                      <span class="profile-offer-dates">
                        {{ $offer->start_date->format('M j') }} – {{ $offer->end_date->format('M j, Y') }}
                      </span>
                      <div class="profile-offer-actions">
                        @if($hasOfferLink)
                          <a href="{{ $offerUrl }}" class="btn btn-primary btn-sm" target="_blank" rel="noopener">Explore</a>
                        @endif
                      </div>
                    </div>
                  </article>
                @endforeach
              </div>
            </div>
          @else
            <p class="profile-empty-note">No active offers available right now.</p>
          @endif
        </div>
      </div>
    </main>
  </div>

  @if($isOwner)
    <a href="{{ route('front.users.profile') }}" class="profile-fab" aria-label="Edit profile">&#9881;</a>
  @endif

  <div class="team-profile-modal" id="teamProfileModal" hidden>
    <div class="team-profile-modal-backdrop js-team-modal-close"></div>
    <div class="team-profile-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="teamModalName">
      <button type="button" class="team-profile-modal-close js-team-modal-close" aria-label="Close">&times;</button>
      <div class="team-profile-modal-body">
        <div class="team-profile-modal-header">
          <div class="team-profile-modal-avatar-wrap">
            <img id="teamModalImage" class="team-profile-modal-avatar" alt="">
            <div id="teamModalInitials" class="team-profile-modal-initials"></div>
          </div>
          <div class="team-profile-modal-identity">
            <h3 id="teamModalName"></h3>
            <p id="teamModalDesignation" class="team-profile-modal-role"></p>
          </div>
        </div>

        <ul class="team-profile-modal-meta">
          <li id="teamModalPhoneRow" hidden><span>Mobile</span><strong id="teamModalPhone"></strong></li>
          <li id="teamModalEmailRow" hidden><span>Email</span><strong id="teamModalEmail"></strong></li>
          <li id="teamModalDeptRow" hidden><span>Department</span><strong id="teamModalDept"></strong></li>
        </ul>

        <div id="teamModalBusinessDescRow" class="team-profile-modal-desc" hidden>
          <span>Business Description</span>
          <p id="teamModalBusinessDesc"></p>
        </div>

        <div class="team-profile-modal-company">
          <div class="team-profile-modal-company-top">
            @if($logoUrl)
              <img src="{{ $logoUrl }}" alt="{{ $profile->company_name }}" class="team-profile-modal-company-logo">
            @else
              <div class="team-profile-modal-company-fallback">{{ $initials ?: 'JG' }}</div>
            @endif
            <div class="team-profile-modal-company-text">
              <strong>{{ $profile->company_name }}</strong>
              @if($fullAddress)
                <span id="teamModalCompanyAddress">{{ $fullAddress }}</span>
              @elseif($location)
                <span id="teamModalCompanyAddress">{{ $location }}</span>
              @endif
            </div>
          </div>
        </div>

        @if($isOwner)
          <button
            type="button"
            class="btn btn-accent btn-block"
            id="downloadVisitingCardBtn"
            data-company="{{ $profile->company_name }}"
            data-logo="{{ $logoUrl }}"
            data-address="{{ $fullAddress ?: $location }}"
          >Download Digital Visiting Card</button>
        @endif
      </div>
    </div>
  </div>

  <div class="listing-gallery-modal" id="listingGalleryModal" hidden>
    <div class="listing-gallery-modal-backdrop js-listing-gallery-close"></div>
    <div class="listing-gallery-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="listingGalleryTitle">
      <div class="listing-gallery-modal-top">
        <div>
          <h3 id="listingGalleryTitle">Photos</h3>
          <p id="listingGalleryCounter" class="listing-gallery-counter"></p>
        </div>
        <button type="button" class="listing-gallery-modal-close js-listing-gallery-close" aria-label="Close">&times;</button>
      </div>
      <div class="listing-gallery-stage">
        <button type="button" class="listing-gallery-nav listing-gallery-prev" id="listingGalleryPrev" aria-label="Previous photo">‹</button>
        <img id="listingGalleryMain" class="listing-gallery-main" alt="">
        <button type="button" class="listing-gallery-nav listing-gallery-next" id="listingGalleryNext" aria-label="Next photo">›</button>
      </div>
      <div class="listing-gallery-thumbs" id="listingGalleryThumbs"></div>
    </div>
  </div>
@endsection

@push('scripts')
<script src="{{ asset('front/assets/js/profile-qr-share.js') }}?v=7"></script>
<script>
(function () {
  document.querySelectorAll('.js-profile-offer-explore').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var target = btn.getAttribute('data-tab-target') || 'overview';
      var tabBtn = document.querySelector('#profileTabs button[data-tab="' + target + '"]');
      if (tabBtn) tabBtn.click();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  });
})();
</script>
<script>
(function () {
  var modal = document.getElementById('listingGalleryModal');
  if (!modal) return;

  var titleEl = document.getElementById('listingGalleryTitle');
  var counterEl = document.getElementById('listingGalleryCounter');
  var mainEl = document.getElementById('listingGalleryMain');
  var thumbsEl = document.getElementById('listingGalleryThumbs');
  var prevBtn = document.getElementById('listingGalleryPrev');
  var nextBtn = document.getElementById('listingGalleryNext');
  var images = [];
  var index = 0;

  function updateView() {
    if (!images.length) return;
    mainEl.src = images[index];
    mainEl.alt = (titleEl.textContent || 'Photo') + ' ' + (index + 1);
    counterEl.textContent = (index + 1) + ' / ' + images.length;
    prevBtn.hidden = images.length < 2;
    nextBtn.hidden = images.length < 2;
    thumbsEl.querySelectorAll('button').forEach(function (btn, i) {
      btn.classList.toggle('is-active', i === index);
    });
  }

  function openGallery(btn) {
    var raw = btn.getAttribute('data-images') || '[]';
    try {
      images = JSON.parse(raw);
    } catch (e) {
      images = [];
    }
    if (!Array.isArray(images) || !images.length) return;

    titleEl.textContent = btn.getAttribute('data-title') || 'Photos';
    index = 0;
    thumbsEl.innerHTML = '';
    images.forEach(function (src, i) {
      var thumb = document.createElement('button');
      thumb.type = 'button';
      thumb.className = 'listing-gallery-thumb' + (i === 0 ? ' is-active' : '');
      thumb.setAttribute('aria-label', 'Photo ' + (i + 1));
      thumb.innerHTML = '<img src="' + src + '" alt="">';
      thumb.addEventListener('click', function () {
        index = i;
        updateView();
      });
      thumbsEl.appendChild(thumb);
    });

    updateView();
    modal.hidden = false;
    document.body.classList.add('listing-gallery-open');
  }

  function closeGallery() {
    modal.hidden = true;
    document.body.classList.remove('listing-gallery-open');
    images = [];
    index = 0;
    mainEl.removeAttribute('src');
  }

  function showPrev() {
    if (images.length < 2) return;
    index = (index - 1 + images.length) % images.length;
    updateView();
  }

  function showNext() {
    if (images.length < 2) return;
    index = (index + 1) % images.length;
    updateView();
  }

  document.querySelectorAll('.js-listing-gallery-open').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      openGallery(btn);
    });
  });

  modal.querySelectorAll('.js-listing-gallery-close').forEach(function (el) {
    el.addEventListener('click', closeGallery);
  });

  prevBtn.addEventListener('click', showPrev);
  nextBtn.addEventListener('click', showNext);

  document.addEventListener('keydown', function (e) {
    if (modal.hidden) return;
    if (e.key === 'Escape') closeGallery();
    if (e.key === 'ArrowLeft') showPrev();
    if (e.key === 'ArrowRight') showNext();
  });
})();
</script>
<script>
(function () {
  function loadImage(src) {
    return new Promise(function (resolve) {
      if (!src) return resolve(null);
      var img = new Image();
      img.crossOrigin = 'anonymous';
      img.onload = function () { resolve(img); };
      img.onerror = function () { resolve(null); };
      img.src = src;
    });
  }

  function wrapText(ctx, text, maxWidth, maxLines) {
    var words = String(text || '').split(/\s+/);
    var lines = [];
    var line = '';
    var limit = maxLines || 2;
    words.forEach(function (word) {
      var test = line ? line + ' ' + word : word;
      if (ctx.measureText(test).width > maxWidth && line) {
        lines.push(line);
        line = word;
      } else {
        line = test;
      }
    });
    if (line) lines.push(line);
    return lines.slice(0, limit);
  }

  function roundedRect(ctx, x, y, w, h, r) {
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.arcTo(x + w, y, x + w, y + h, r);
    ctx.arcTo(x + w, y + h, x, y + h, r);
    ctx.arcTo(x, y + h, x, y, r);
    ctx.arcTo(x, y, x + w, y, r);
    ctx.closePath();
  }

  var modal = document.getElementById('teamProfileModal');
  if (!modal) return;

  var imgEl = document.getElementById('teamModalImage');
  var initialsEl = document.getElementById('teamModalInitials');
  var nameEl = document.getElementById('teamModalName');
  var roleEl = document.getElementById('teamModalDesignation');
  var phoneRow = document.getElementById('teamModalPhoneRow');
  var phoneEl = document.getElementById('teamModalPhone');
  var emailRow = document.getElementById('teamModalEmailRow');
  var emailEl = document.getElementById('teamModalEmail');
  var deptRow = document.getElementById('teamModalDeptRow');
  var deptEl = document.getElementById('teamModalDept');
  var descRow = document.getElementById('teamModalBusinessDescRow');
  var descEl = document.getElementById('teamModalBusinessDesc');
  var downloadBtn = document.getElementById('downloadVisitingCardBtn');
  var currentMember = null;

  function setRow(row, el, value, prefix) {
    if (value) {
      el.textContent = (prefix || '') + value;
      row.hidden = false;
    } else {
      row.hidden = true;
      el.textContent = '';
    }
  }

  function openModal(btn) {
    currentMember = {
      name: btn.getAttribute('data-name') || '',
      designation: btn.getAttribute('data-designation') || '',
      phone: btn.getAttribute('data-phone') || '',
      email: btn.getAttribute('data-email') || '',
      department: btn.getAttribute('data-department') || '',
      info: btn.getAttribute('data-info') || '',
      image: btn.getAttribute('data-image') || '',
      initials: btn.getAttribute('data-initials') || '',
      color: btn.getAttribute('data-color') || '#1A428A'
    };

    nameEl.textContent = currentMember.name;
    roleEl.textContent = currentMember.designation || '-';
    roleEl.title = currentMember.designation || '';

    imgEl.classList.remove('is-visible');
    initialsEl.classList.remove('is-visible');

    if (currentMember.image) {
      imgEl.src = currentMember.image;
      imgEl.alt = currentMember.name;
      imgEl.classList.add('is-visible');
    } else {
      imgEl.removeAttribute('src');
      initialsEl.textContent = currentMember.initials;
      initialsEl.style.background = currentMember.color;
      initialsEl.classList.add('is-visible');
    }

    var phone = String(currentMember.phone || '').replace(/\D+/g, '');
    var phoneLabel = phone.length === 10 ? ('+91 ' + phone) : (phone ? ('+91 ' + phone) : '');
    setRow(phoneRow, phoneEl, phoneLabel);
    setRow(emailRow, emailEl, currentMember.email);
    setRow(deptRow, deptEl, currentMember.department);

    if (currentMember.info && descRow && descEl) {
      descEl.textContent = currentMember.info;
      descRow.hidden = false;
    } else if (descRow && descEl) {
      descEl.textContent = '';
      descRow.hidden = true;
    }

    modal.hidden = false;
    document.body.classList.add('team-modal-open');
  }

  function closeModal() {
    modal.hidden = true;
    document.body.classList.remove('team-modal-open');
    currentMember = null;
  }

  document.querySelectorAll('.js-team-view-profile').forEach(function (btn) {
    btn.addEventListener('click', function () { openModal(btn); });
  });

  modal.querySelectorAll('.js-team-modal-close').forEach(function (el) {
    el.addEventListener('click', closeModal);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.hidden) closeModal();
  });

  function downloadVisitingCard() {
    if (!currentMember || !downloadBtn) return;

    var company = downloadBtn.getAttribute('data-company') || '';
    var logoUrl = downloadBtn.getAttribute('data-logo') || '';
    var address = downloadBtn.getAttribute('data-address') || '';
    var width = 900;
    var height = 560;

    Promise.all([
      loadImage(logoUrl),
      loadImage(currentMember.image)
    ]).then(function (images) {
      var logoImg = images[0];
      var memberImg = images[1];
      var canvas = document.createElement('canvas');
      canvas.width = width;
      canvas.height = height;
      var ctx = canvas.getContext('2d');

      var grad = ctx.createLinearGradient(0, 0, width, height);
      grad.addColorStop(0, '#003366');
      grad.addColorStop(1, '#1A428A');
      ctx.fillStyle = grad;
      ctx.fillRect(0, 0, width, height);

      ctx.fillStyle = '#ffffff';
      roundedRect(ctx, 28, 28, width - 56, height - 56, 18);
      ctx.fill();

      ctx.fillStyle = '#1A428A';
      ctx.fillRect(28, 28, 12, height - 56);

      var companyX = 70;
      var companyY = 52;
      if (logoImg) {
        ctx.save();
        roundedRect(ctx, companyX, companyY, 48, 48, 8);
        ctx.clip();
        ctx.drawImage(logoImg, companyX, companyY, 48, 48);
        ctx.restore();
        companyX += 62;
      }
      ctx.fillStyle = '#003366';
      ctx.font = 'bold 26px Arial, sans-serif';
      ctx.fillText(company, companyX, companyY + 22);
      if (address) {
        ctx.fillStyle = '#666';
        ctx.font = '16px Arial, sans-serif';
        wrapText(ctx, address, 700, 2).forEach(function (line, i) {
          ctx.fillText(line, companyX, companyY + 46 + (i * 20));
        });
      }

      var photoX = 90;
      var photoY = 170;
      var photoSize = 150;
      ctx.save();
      ctx.beginPath();
      ctx.arc(photoX + photoSize / 2, photoY + photoSize / 2, photoSize / 2, 0, Math.PI * 2);
      ctx.closePath();
      ctx.clip();
      if (memberImg) {
        ctx.drawImage(memberImg, photoX, photoY, photoSize, photoSize);
      } else {
        ctx.fillStyle = currentMember.color || '#1A428A';
        ctx.fillRect(photoX, photoY, photoSize, photoSize);
        ctx.fillStyle = '#fff';
        ctx.font = 'bold 48px Arial, sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(currentMember.initials || '', photoX + photoSize / 2, photoY + photoSize / 2);
        ctx.textAlign = 'left';
        ctx.textBaseline = 'alphabetic';
      }
      ctx.restore();

      var textX = 280;
      var textY = 200;
      ctx.fillStyle = '#003366';
      ctx.font = 'bold 34px Arial, sans-serif';
      ctx.fillText(currentMember.name || '', textX, textY);

      ctx.fillStyle = '#F7941D';
      ctx.font = '600 20px Arial, sans-serif';
      var roleLines = wrapText(ctx, currentMember.designation || '', 540);
      roleLines.forEach(function (line, i) {
        ctx.fillText(line, textX, textY + 40 + (i * 26));
      });

      var detailY = textY + 40 + (roleLines.length * 26) + 28;
      ctx.fillStyle = '#555';
      ctx.font = '20px Arial, sans-serif';
      var phone = String(currentMember.phone || '').replace(/\D+/g, '');
      if (phone) {
        ctx.fillText('Mobile: +91 ' + phone, textX, detailY);
        detailY += 32;
      }
      if (currentMember.email) {
        ctx.fillText('Email: ' + currentMember.email, textX, detailY);
      }

      ctx.fillStyle = '#1A428A';
      ctx.font = 'bold 15px Arial, sans-serif';
      ctx.fillText('Just Goom Digital Visiting Card', 70, height - 52);

      var link = document.createElement('a');
      var safeName = (currentMember.name || 'team-member').replace(/[^\w\-]+/g, '-').toLowerCase();
      link.download = safeName + '-visiting-card.png';
      link.href = canvas.toDataURL('image/png');
      link.click();
    });
  }

  if (downloadBtn) {
    downloadBtn.addEventListener('click', downloadVisitingCard);
  }
})();
</script>
@endpush
