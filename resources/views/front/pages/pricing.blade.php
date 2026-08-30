@extends('front.layouts.app')

@section('title', 'Pricing — Just Goom LLP')
@section('meta_description', 'Compare Silver, Gold, and Platinum business listing plans. View India prices in INR or Global prices in USD.')
@section('body_attrs', 'class="pricing-page" data-page="pricing"')

@php
  $inrToUsd = \App\Support\PricingCatalog::USD_RATE;
  $formatInr = [\App\Support\PricingCatalog::class, 'formatInr'];
  $formatUsd = [\App\Support\PricingCatalog::class, 'formatUsd'];
  $plans = \App\Support\PricingCatalog::plans();
  $comparisonRows = \App\Support\PricingCatalog::comparisonRows();
  $addons = \App\Support\PricingCatalog::addons();
@endphp

@section('content')
  <section class="page-hero pricing-hero">
    <div class="container">
      <nav class="breadcrumb">
        <a href="{{ route('front.home') }}">Home</a>
        <span class="breadcrumb-sep">›</span>
        <span>Pricing</span>
      </nav>
      <h1>Business Listing Plans</h1>
      <p>Choose Silver, Gold, or Platinum — billed yearly. Switch between India (INR) and Global (USD) pricing.</p>
    </div>
    <div class="pixel-deco orange"><span></span><span></span><span></span><span></span></div>
  </section>

  <div class="pricing-page-content" data-active-region="india">
    <section class="section">
      <div class="container">
        <div class="pricing-region-wrap">
          <div class="pricing-region" role="tablist" aria-label="Pricing region">
            <button type="button" class="pricing-region-btn active" data-region="india" role="tab" aria-selected="true">India</button>
            <button type="button" class="pricing-region-btn" data-region="global" role="tab" aria-selected="false">Global</button>
          </div>
          <p class="pricing-rate-note" hidden>Global prices are shown in USD, converted from INR at ₹{{ $inrToUsd }} = $1.</p>
        </div>

        <div class="plans-grid pricing-plans-grid">
          @foreach($plans as $plan)
            <article class="plan-card pricing-plan-card{{ !empty($plan['popular']) ? ' plan-card-featured' : '' }} pricing-plan-{{ $plan['key'] }}">
              @if(!empty($plan['popular']))
                <span class="plan-popular">Most Popular</span>
              @endif
              <div class="plan-card-header">
                <span class="plan-icon">{{ $plan['icon'] }}</span>
                <h3>{{ $plan['name'] }}</h3>
                <p class="plan-price">
                  <span class="js-region-price" data-india="{{ $formatInr($plan['inr']) }}" data-global="{{ $formatUsd($plan['inr']) }}">{{ $formatInr($plan['inr']) }}</span>
                  <span>/year</span>
                </p>
                <p class="pricing-plan-tagline">{{ $plan['tagline'] }}</p>
              </div>
              <p class="pricing-includes-label">{{ $plan['includes_label'] }}</p>
              <ul class="plan-features">
                @foreach($plan['features'] as $feature)
                  <li><span class="plan-check">✓</span> {{ $feature }}</li>
                @endforeach
              </ul>
              <a href="{{ route('front.register') }}" class="btn {{ $plan['cta_class'] }} btn-block">{{ $plan['cta'] }}</a>
            </article>
          @endforeach
        </div>
      </div>
    </section>

    <section class="section section-alt">
      <div class="container">
        <div class="plans-comparison">
          <h3>Compare plans at a glance</h3>
          <div class="comparison-table-wrap">
            <table class="comparison-table pricing-comparison-table">
              <thead>
                <tr>
                  <th>Feature</th>
                  @foreach($plans as $plan)
                    <th>
                      {{ $plan['name'] }}
                      <span class="pricing-table-price js-region-price" data-india="{{ $formatInr($plan['inr']) }}" data-global="{{ $formatUsd($plan['inr']) }}">{{ $formatInr($plan['inr']) }}</span>
                    </th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @foreach($comparisonRows as $row)
                  <tr>
                    @foreach($row as $cell)
                      @php $isDash = $cell === '—'; $isCheck = $cell === '✓'; @endphp
                      <td class="{{ $isDash ? 'pricing-cell-empty' : ($isCheck ? 'pricing-cell-yes' : '') }}">{{ $cell }}</td>
                    @endforeach
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="section-header text-center about-section-head">
          <div>
            <h2 class="section-title">Additional paid add-ons</h2>
            <p class="section-subtitle">Generate extra visibility without changing your package price</p>
          </div>
        </div>
        <div class="pricing-addons-grid">
          @foreach($addons as $addon)
            <article class="pricing-addon-card">
              <h3>{{ $addon['name'] }}</h3>
              @if(!empty($addon['custom']))
                <p class="pricing-addon-price">Custom pricing</p>
              @else
                <p class="pricing-addon-price">
                  <span class="js-region-price" data-india="{{ $formatInr($addon['inr']).$addon['period'] }}" data-global="{{ $formatUsd($addon['inr']).$addon['period'] }}">{{ $formatInr($addon['inr']).$addon['period'] }}</span>
                </p>
              @endif
            </article>
          @endforeach
        </div>
        <div class="pricing-addons-cta">
          <a href="{{ route('front.contact') }}" class="btn btn-outline-primary">Ask about add-ons</a>
        </div>
      </div>
    </section>

    <section class="section section-alt">
      <div class="container">
        <div class="b2b-cta-grid">
          <div class="b2b-cta-card b2b-cta-buyer">
            <span class="b2b-cta-label">Not sure which plan?</span>
            <h3>Talk to our team</h3>
            <p>We’ll help you pick the right listing package for your business size and goals.</p>
            <a href="{{ route('front.contact') }}" class="btn btn-accent">Contact Us →</a>
          </div>
          <div class="b2b-cta-card b2b-cta-seller">
            <span class="b2b-cta-label">Ready to list</span>
            <h3>Start with a free profile</h3>
            <p>Register your business and upgrade to Silver, Gold, or Platinum when you are ready.</p>
            <a href="{{ route('front.register') }}" class="btn btn-primary">Register Free →</a>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
