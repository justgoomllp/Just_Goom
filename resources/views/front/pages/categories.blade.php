@extends('front.layouts.app')

@section('title', 'All Categories — Just Goom LLP')
@section('meta_description', 'Browse 24+ B2B business sectors and 170+ subcategories on JustGoom — find verified suppliers across India.')
@section('body_attrs', 'class="categories-page" data-page="categories"')

@section('content')
<!-- Page Hero -->
  <section class="page-hero categories-hero">
    <div class="container">
      <nav class="breadcrumb">
        <a href="{{ route('front.home') }}">Home</a>
        <span class="breadcrumb-sep">›</span>
        <span>All Categories</span>
      </nav>
      <h1>Browse All Categories</h1>
      <p>Explore {{ $catalogStats['sectors'] }}+ business sectors and {{ $catalogStats['subcategories'] }}+ subcategories — find verified suppliers and service providers across India.</p>
    </div>
    <div class="pixel-deco orange"><span></span><span></span><span></span><span></span></div>
  </section>

  <!-- Stats -->
  <div class="stats-bar categories-stats">
    <div class="container stats-grid">
      <div class="stat-item">
        <div class="stat-num" id="statSectors">{{ $catalogStats['sectors'] }}+</div>
        <div class="stat-label">Business Sectors</div>
      </div>
      <div class="stat-item">
        <div class="stat-num" id="statSubs">{{ $catalogStats['subcategories'] }}+</div>
        <div class="stat-label">Subcategories</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">{{ $catalogStats['profiles'] }}+</div>
        <div class="stat-label">Verified Profiles</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">{{ $catalogStats['cities'] }}</div>
        <div class="stat-label">Cities Covered</div>
      </div>
    </div>
  </div>

  <!-- Popular Categories Strip -->
  <section class="categories-popular-strip">
    <div class="container">
      <div class="categories-popular-head">
        <h2>Popular Sectors</h2>
        <span>Quick access to top categories</span>
      </div>
      <div class="categories-popular-scroll" id="popularSectorsStrip"></div>
    </div>
  </section>

  <!-- Main Categories Layout -->
  <section class="categories-main-section">
    <div class="container categories-main-layout">

      <!-- Sidebar -->
      <aside class="categories-sidebar" id="categoriesSidebar">
        <div class="categories-sidebar-head">
          <h3>Filter by Sector</h3>
          <button type="button" class="categories-sidebar-reset" id="sectorReset">Clear</button>
        </div>
        <div class="categories-sidebar-search">
          <input type="text" id="sidebarSearch" placeholder="Filter sectors...">
        </div>
        <nav class="categories-sidebar-list" id="sectorSidebarList" aria-label="Sector filters"></nav>
      </aside>

      <!-- Content -->
      <div class="categories-main-content">
        <div class="categories-toolbar">
          <div class="globy-sectors-search categories-search-wide">
            <span class="globy-sectors-search-icon">🔍</span>
            <input type="text" id="sectorSearch" placeholder="Search categories or subcategories...">
          </div>
          <a href="{{ route('front.all-profiles') }}" class="btn btn-primary btn-sm">View All Profiles</a>
        </div>
        <p class="globy-sectors-count" id="sectorsCount"></p>
        <div class="globy-sectors-grid categories-sectors-grid" id="allSectorsGrid"></div>
        <p class="globy-sectors-empty categories-no-results" id="sectorsEmpty" hidden>No categories match your search. Try a different keyword.</p>
      </div>

    </div>
  </section>

  <!-- CTA -->
  <section class="section section-alt">
    <div class="container">
      <div class="b2b-cta-grid">
        <div class="b2b-cta-card b2b-cta-buyer">
          <span class="b2b-cta-label">For Buyers</span>
          <h3>Can't Find Your Category?</h3>
          <p>Post your requirement and we'll help you connect with the right verified businesses.</p>
          <a href="{{ route('front.contact') }}" class="btn btn-accent">Post Requirement →</a>
        </div>
        <div class="b2b-cta-card b2b-cta-seller">
          <span class="b2b-cta-label">For Sellers</span>
          <h3>List Under Any Category</h3>
          <p>Register your business for free and choose the sector that fits your services.</p>
          <a href="{{ route('front.register') }}" class="btn btn-primary">Register Free →</a>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
@include('front.partials.catalog-data')
<script src="{{ asset('front/assets/js/categories-data.js') }}"></script>
<script src="{{ asset('front/assets/js/categories-render.js') }}"></script>
@endpush
