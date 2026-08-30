@extends('front.layouts.app')

@section('title', 'All Business Profiles — Just Goom LLP')
@section('meta_description', 'Browse verified business profiles on JustGoom — filter by category, city, and verification status across India.')
@section('body_attrs', 'class="all-profiles-page" data-page="all-profiles"')

@section('content')
<!-- Page Hero -->
  <section class="page-hero profiles-hero">
    <div class="container">
      <nav class="breadcrumb">
        <a href="{{ route('front.home') }}">Home</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('front.categories') }}">Categories</a>
        <span class="breadcrumb-sep">›</span>
        <span>All Profiles</span>
      </nav>
      <h1>All Business Profiles</h1>
      <p id="profilesSubtitle">Browse verified business profiles across all categories</p>
    </div>
    <div class="pixel-deco orange"><span></span><span></span><span></span><span></span></div>
  </section>

  <!-- Stats -->
  <div class="stats-bar profiles-stats">
    <div class="container stats-grid">
      <div class="stat-item">
        <div class="stat-num">{{ $profileStats['total'] }}</div>
        <div class="stat-label">Total Profiles</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">{{ $profileStats['verified'] }}</div>
        <div class="stat-label">Verified</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">{{ $profileStats['cities'] }}</div>
        <div class="stat-label">Cities</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">{{ $profileStats['categories'] }}</div>
        <div class="stat-label">Categories</div>
      </div>
    </div>
  </div>

  <!-- Main Layout -->
  <div class="profiles-filter-overlay" id="profilesFilterOverlay" hidden aria-hidden="true"></div>
  <section class="profiles-main-section company-profiles-section company-profiles-page">
    <div class="container profiles-main-layout">

      <!-- Sidebar Filters -->
      <aside class="profiles-sidebar">
        <div class="profiles-sidebar-head">
          <h3>Filters</h3>
          <button type="button" class="profiles-sidebar-reset filter-reset">Clear All</button>
        </div>

        <div class="profiles-sidebar-body" id="profileFilters">
          <div class="filter-group">
            <label class="filter-label">Business Category</label>
            <div class="filter-chips" data-filter-group="category" data-single="true">
              <span class="chip active" data-value="all">All</span>
              @foreach($filterCategories as $category)
                <span class="chip" data-value="{{ $category->slug }}">{{ $category->name }}</span>
              @endforeach
            </div>
          </div>

          <div class="filter-group" id="subCategoryFilterGroup" hidden>
            <label class="filter-label" for="profileSubCategorySelect">Subcategory</label>
            <select class="filter-select" id="profileSubCategorySelect" data-filter="subcategory">
              <option value="all">All Subcategories</option>
            </select>
          </div>

          <div class="filter-group">
            <label class="filter-label">City</label>
            <select class="filter-select" data-filter="locality">
              <option value="all cities">All Cities</option>
              @foreach($filterCities as $city)
                <option value="{{ strtolower($city) }}">{{ $city }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-group">
            <label class="filter-label">Verification</label>
            <div class="filter-chips" data-filter-group="verified" data-single="true">
              <span class="chip active" data-value="all">All</span>
              <span class="chip" data-value="verified only">Verified Only</span>
            </div>
          </div>

          <div class="filter-group">
            <label class="filter-label" for="categoryTimeFilterSelect">Date Added</label>
            <select class="filter-select profiles-time-select" id="categoryTimeFilterSelect">
              <option value="all">Any time</option>
              <option value="today">Today</option>
              <option value="week">Last 7 days</option>
              <option value="month">Last 30 days</option>
              <option value="year">Last 12 months</option>
            </select>
          </div>

          <div class="profiles-sidebar-actions">
            <button type="button" class="btn btn-primary btn-sm btn-block btn-apply-filters">Apply Filters</button>
          </div>
        </div>

        <div class="profiles-sidebar-links">
          <h4>Browse Categories</h4>
          <a href="{{ route('front.categories') }}">View All Categories →</a>
          <a href="{{ route('front.register') }}">List Your Business →</a>
        </div>
      </aside>

      <!-- Content -->
      <div class="profiles-main-content">
        <div class="profiles-toolbar company-toolbar">
          <div class="company-search-wrap profiles-search-wide">
            <span class="company-search-icon">🔍</span>
            <input type="text" id="categoryCompanySearch" placeholder="Search by business name or category...">
          </div>
          <div class="company-toolbar-actions">
            <div class="view-toggle">
              <button type="button" id="categoryViewGrid" class="active" title="Grid view">▦</button>
              <button type="button" id="categoryViewList" title="List view">☰</button>
            </div>
            <button type="button" class="btn-filters profiles-mobile-filters" id="categoryFiltersToggle" aria-label="Toggle filters">+ Filters</button>
          </div>
        </div>

        <div class="profiles-results-bar">
          @php
            $totalListedProfiles = count($companyProfiles ?? []);
            $profilesPerPage = 12;
            $profilesTotalPages = max(1, (int) ceil(max($totalListedProfiles, 1) / $profilesPerPage));
            $profilesShowingEnd = min($profilesPerPage, $totalListedProfiles);
          @endphp
          <p class="company-count" id="categoryCompanyCount">
            @if($totalListedProfiles === 0)
              No profiles found
            @else
              Showing 1–{{ $profilesShowingEnd }} of {{ $totalListedProfiles }} profiles
            @endif
          </p>
          <span class="profiles-page-info" id="profilesPageInfo">
            @if($totalListedProfiles > 0)
              Page 1 of {{ $profilesTotalPages }} · {{ $profilesPerPage }} per page · 3 per row
            @endif
          </span>
          <a href="{{ route('front.register') }}" class="profiles-add-link">+ Add Your Profile</a>
        </div>

        <div class="company-grid profiles-grid profiles-grid-3" id="categoryCompanyGrid"></div>

        <p class="profiles-empty" id="profilesEmpty" @if($totalListedProfiles > 0) hidden @endif>No profiles match your filters. Try adjusting your search or filters.</p>

        <nav class="pagination company-pagination profiles-pagination" id="profilesPagination" aria-label="Profile pages"></nav>
      </div>

    </div>
  </section>

  <!-- CTA -->
  <section class="section section-alt">
    <div class="container">
      <div class="b2b-cta-grid">
        <div class="b2b-cta-card b2b-cta-buyer">
          <span class="b2b-cta-label">For Buyers</span>
          <h3>Can't Find the Right Supplier?</h3>
          <p>Post your requirement and connect with verified businesses that match your needs.</p>
          <a href="{{ route('front.contact') }}" class="btn btn-accent">Post Requirement →</a>
        </div>
        <div class="b2b-cta-card b2b-cta-seller">
          <span class="b2b-cta-label">For Sellers</span>
          <h3>Get Listed Today</h3>
          <p>Join {{ max($profileStats['total'], 1) }}+ businesses on JustGoom. Free registration, verified badge, and buyer inquiries.</p>
          <a href="{{ route('front.register') }}" class="btn btn-primary">Register Free →</a>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
@include('front.partials.catalog-data')
<script src="{{ asset('front/assets/js/company-profiles.js') }}"></script>
@endpush
