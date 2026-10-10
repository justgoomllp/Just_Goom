@extends('front.layouts.app')

@section('title', 'All Offers & Promotions — Just Goom LLP')
@section('meta_description', 'Browse live offers and promotions from verified JustGoom businesses.')
@section('body_attrs', 'class="offers-page" data-page="offers"')

@push('styles')
  <link rel="stylesheet" href="https://unpkg.com/galaxyjs@3/galaxy.css">
@endpush

@section('content')
<section class="page-hero offers-hero" id="offersGalaxyHero">
  <div id="offersGalaxyCanvas" class="offers-galaxy-canvas" aria-hidden="true"></div>
  <div class="container">
    <nav class="breadcrumb">
      <a href="{{ route('front.home') }}">Home</a>
      <span class="breadcrumb-sep">›</span>
      <span>All Offers</span>
    </nav>
    <h1>All running offers</h1>
    <p>Live promotions from registered businesses. Open a card to view the company profile and claim the deal.</p>
  </div>
</section>

<div class="container offers-all-wrap">
  @php
    $fallbackOfferImages = [
      asset('front/assets/images/cat-business.jpg'),
      asset('front/assets/images/cat-food.jpg'),
      asset('front/assets/images/cat-health.jpg'),
      asset('front/assets/images/cat-real-estate.jpg'),
    ];
    $defaultLogo = asset('front/assets/images/justgoom-logo.png');
  @endphp

  @if($offers->isEmpty())
    <div class="article-empty-state">
      <h2>No running offers right now</h2>
      <p>Check back soon for new promotions from JustGoom members.</p>
      <a href="{{ route('front.home') }}" class="btn btn-primary" style="margin-top:16px;">Back to home</a>
    </div>
  @else
    <p class="offers-all-count">Showing {{ $offers->firstItem() }}–{{ $offers->lastItem() }} of {{ $offers->total() }} live offers</p>
    <div class="offers-all-grid">
      @foreach($offers as $index => $offer)
        @php
          $company = $offer->user?->companyProfile;
          $companyName = $company?->company_name
            ?: trim(($offer->user?->fname ?? '') . ' ' . ($offer->user?->lname ?? ''))
            ?: 'JustGoom Member';
          $companyTagLine = $company?->tagline ?: $companyName;
          $offerUrl = $company?->slug
            ? route('front.profile.show', $company->slug) . '#offers'
            : route('front.all-profiles');
          $image = $offer->banner_image
            ? asset($offer->banner_image)
            : $fallbackOfferImages[$index % count($fallbackOfferImages)];
          $logo = $company?->logo ? asset($company->logo) : $defaultLogo;
        @endphp
        <a href="{{ $offerUrl }}" class="offer-promo-card">
          <div class="offer-promo-media">
            <img class="offer-promo-bg" src="{{ $image }}" alt="" loading="lazy" aria-hidden="true">
            <div class="offer-promo-overlay" aria-hidden="true"></div>
            @if($offer->is_featured)
              <span class="offer-promo-ad">Featured</span>
            @else
              <span class="offer-promo-ad">Offer</span>
            @endif
          </div>
          <div class="offer-promo-content">
            <div class="offer-promo-brand">
              <div class="offer-promo-logo">
                <img src="{{ $logo }}" alt="{{ $companyName }}">
              </div>
              <span class="offer-promo-company" title="{{ $companyName }}">{{ \Illuminate\Support\Str::limit($companyName, 28) }}</span>
            </div>
            <h3 class="offer-promo-title" title="{{ $offer->title }}">{{ \Illuminate\Support\Str::limit($offer->title, 55) }}</h3>
            <p class="offer-promo-tagline" title="{{ $companyTagLine }}">{{ \Illuminate\Support\Str::limit(strip_tags($offer->description ?: $companyTagLine), 80) }}</p>
            <span class="offer-promo-cta">View offer <span aria-hidden="true">→</span></span>
          </div>
        </a>
      @endforeach
    </div>

    @if($offers->hasPages())
      <div class="blogs-pagination">
        {{ $offers->links('front.partials.pagination') }}
      </div>
    @endif
  @endif
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/galaxyjs@3/galaxy.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var canvas = document.getElementById('offersGalaxyCanvas');
    if (!canvas || typeof Galaxy === 'undefined' || typeof Galaxy.create !== 'function') return;

    var options = {
      speed: 0.55,
      colors: ['#003366', '#1A428A', '#F7941D', '#f8fafc']
    };

    try {
      Galaxy.create('galaxyMerge', canvas, options);
    } catch (err) {
      Galaxy.create('nebula', canvas, options);
    }
  });
</script>
@endpush
