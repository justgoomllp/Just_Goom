@extends('front.layouts.user')

@section('title', 'Subscription — Just Goom')
@section('page_title', 'Subscription')
@section('body_attrs', 'class="user-panel-body" data-page="subscription" data-title="Subscription"')

@section('content')
@php
  $formatInr = [\App\Support\PricingCatalog::class, 'formatInr'];
  $formatUsd = [\App\Support\PricingCatalog::class, 'formatUsd'];
  $catalogByName = collect($catalogPlans)->keyBy('name');
  $currentName = $currentPlan?->name ?? 'None';
  $currentMeta = $catalogByName->get($currentName);
@endphp
<div class="user-content pricing-page-content" data-active-region="india">
      <div class="user-plan-banner">
        <span style="font-size:40px">{{ $currentMeta['icon'] ?? '💳' }}</span>
        <div style="flex:1">
          <h2>{{ $currentPlan?->name ? $currentPlan->name.' Plan' : 'No active plan' }}</h2>
          <p>
            @if($currentUserPlan && ($currentPlan?->rate ?? 0) > 0)
              <span class="js-region-price" data-india="{{ $formatInr((int) $currentPlan->rate) }}" data-global="{{ $formatUsd((int) $currentPlan->rate) }}">{{ $formatInr((int) $currentPlan->rate) }}</span>
              /year
              @if($currentUserPlan?->next_purchase_date)
                · Renews on {{ $currentUserPlan->next_purchase_date->format('M j, Y') }}
              @endif
            @elseif($currentUserPlan)
              Active until {{ $currentUserPlan->next_purchase_date?->format('M j, Y') }}
            @else
              Purchase Silver, Gold, or Platinum to unlock Dashboard and other business pages.
            @endif
          </p>
        </div>
        <a href="#plan-cards" class="user-btn user-btn-default">{{ $currentUserPlan ? 'Manage Plan' : 'Choose a Plan' }}</a>
      </div>

      <div class="pricing-region-wrap">
        <div class="pricing-region" role="tablist" aria-label="Pricing region">
          <button type="button" class="pricing-region-btn active" data-region="india" role="tab" aria-selected="true">India</button>
          <button type="button" class="pricing-region-btn" data-region="global" role="tab" aria-selected="false">Global</button>
        </div>
        <p class="pricing-rate-note" hidden>Global prices are shown in USD, converted from INR at ₹{{ $inrToUsd }} = $1.</p>
      </div>

      <div class="user-plan-row" id="plan-cards">
        @forelse($plans as $plan)
          @php
            $meta = $catalogByName->get($plan->name, [
              'icon' => '📦',
              'key' => strtolower($plan->name),
              'inr' => (int) $plan->rate,
              'tagline' => '',
              'includes_label' => 'Includes:',
              'features' => ['Plan features available after activation'],
              'user_cta_class' => 'user-btn-default',
            ]);
            $isCurrent = (int) $plan->id === (int) ($currentPlan?->id);
            $isPopular = !empty($meta['popular']) && ! $isCurrent;
          @endphp
          <div class="user-plan-card{{ $isCurrent || $isPopular ? ' featured' : '' }} user-plan-{{ $meta['key'] ?? strtolower($plan->name) }}">
            @if($isCurrent)
              <span class="user-plan-badge">Current Plan</span>
            @elseif($isPopular)
              <span class="user-plan-badge">Most Popular</span>
            @endif
            <span style="font-size:28px">{{ $meta['icon'] }}</span>
            <h3>{{ $plan->name }}</h3>
            <div class="price">
              <span class="js-region-price" data-india="{{ $formatInr((int) ($meta['inr'] ?? $plan->rate)) }}" data-global="{{ $formatUsd((int) ($meta['inr'] ?? $plan->rate)) }}">{{ $formatInr((int) ($meta['inr'] ?? $plan->rate)) }}</span>
              <small style="font-size:13px;font-weight:400;color:#888">/year</small>
            </div>
            @if(!empty($meta['tagline']))
              <p class="user-plan-tagline">{{ $meta['tagline'] }}</p>
            @endif
            <p class="user-plan-includes">{{ $meta['includes_label'] ?? 'Includes:' }}</p>
            <ul>
              @foreach($meta['features'] as $feature)
                <li>✓ {{ $feature }}</li>
              @endforeach
            </ul>
            @if($isCurrent)
              <button type="button" class="user-btn user-btn-primary" style="width:100%" disabled>Current Plan</button>
            @elseif($currentPlan && (float) $plan->rate < (float) $currentPlan->rate)
              <p class="user-plan-no-downgrade">Downgrade is not available</p>
            @else
              <form method="POST" action="{{ route('front.users.subscription.order', $plan) }}" class="js-razorpay-checkout">
                @csrf
                <button type="submit" class="user-btn {{ $meta['user_cta_class'] ?? 'user-btn-default' }}" style="width:100%">
                  {{ $currentPlan ? 'Upgrade to '.$plan->name : 'Choose '.$plan->name }}
                </button>
              </form>
            @endif
          </div>
        @empty
          <p class="user-text-muted">No subscription plans are available yet.</p>
        @endforelse
      </div>
      @if(!empty($razorpayTestMode))
        <div class="user-plan-test-note" role="note">
          <strong>Razorpay test mode</strong>
          <p>Visa <code>4111 1111 1111 1111</code> is treated as an international card and this account does not accept international cards.</p>
          <ul>
            <li><strong>Easiest:</strong> choose UPI and pay with <code>success@razorpay</code></li>
            <li><strong>Card:</strong> Mastercard <code>5267 3181 8797 5449</code>, any future expiry, any CVV</li>
          </ul>
          <p>After you click Pay, use <strong>Success</strong> on Razorpay’s mock bank page.</p>
        </div>
      @else
        <p class="user-plan-pay-note">Secure checkout with Razorpay. An invoice email is sent after a successful payment.</p>
      @endif

      <div class="user-panel">
        <div class="user-panel-head">Compare plans at a glance</div>
        <div class="user-panel-body" style="padding:0">
          <div class="user-comparison-wrap">
            <table class="user-table user-pricing-table" style="border:none">
              <thead>
                <tr>
                  <th>Feature</th>
                  @foreach($plans as $plan)
                    @php $meta = $catalogByName->get($plan->name); @endphp
                    <th>
                      {{ $plan->name }}
                      <span class="user-pricing-table-price js-region-price" data-india="{{ $formatInr((int) ($meta['inr'] ?? $plan->rate)) }}" data-global="{{ $formatUsd((int) ($meta['inr'] ?? $plan->rate)) }}">{{ $formatInr((int) ($meta['inr'] ?? $plan->rate)) }}</span>
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

      <div class="user-panel" style="margin-top:20px">
        <div class="user-panel-head">Additional paid add-ons</div>
        <div class="user-panel-body">
          <div class="user-addons-grid">
            @foreach($addons as $addon)
              <article class="user-addon-card">
                <h3>{{ $addon['name'] }}</h3>
                @if(!empty($addon['custom']))
                  <p>Custom pricing</p>
                @else
                  <p>
                    <span class="js-region-price" data-india="{{ $formatInr($addon['inr']).$addon['period'] }}" data-global="{{ $formatUsd($addon['inr']).$addon['period'] }}">{{ $formatInr($addon['inr']).$addon['period'] }}</span>
                  </p>
                @endif
              </article>
            @endforeach
          </div>
        </div>
      </div>

      @if($currentPlan)
        @php $limits = $currentMeta['limits'] ?? []; @endphp
        <div class="user-panel" style="margin-top:20px">
          <div class="user-panel-head">Feature Usage ({{ $currentName }})</div>
          <div class="user-panel-body" style="padding:0">
            <table class="user-table" style="border:none">
              <thead><tr><th>Feature</th><th>Used</th><th>Limit</th></tr></thead>
              <tbody>
                <tr>
                  <td>Services</td>
                  <td>{{ $usage['services'] }}</td>
                  <td>{{ $limits['services'] ?? '—' }}</td>
                </tr>
                <tr>
                  <td>Team Members</td>
                  <td>{{ $usage['team'] }}</td>
                  <td>{{ $limits['team'] ?? '—' }}</td>
                </tr>
                <tr>
                  <td>Documents</td>
                  <td>{{ $usage['documents'] }}</td>
                  <td>{{ $limits['documents'] ?? '—' }}</td>
                </tr>
                <tr>
                  <td>Videos</td>
                  <td>{{ $usage['videos'] }}</td>
                  <td>{{ $limits['videos'] ?? '—' }}</td>
                </tr>
                <tr>
                  <td>Projects</td>
                  <td>{{ $usage['projects'] }}</td>
                  <td>{{ $limits['projects'] ?? '—' }}</td>
                </tr>
                <tr>
                  <td>Articles</td>
                  <td>{{ $usage['articles'] }}</td>
                  <td>{{ $limits['articles'] ?? '—' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      @endif

      <div class="user-panel" style="margin-top:20px" id="payment-logs">
        <div class="user-panel-head">Payment history</div>
        <div class="user-panel-body">
          <p class="user-text-muted" style="margin:0 0 12px;">View Razorpay payments and download invoices from your payment history.</p>
          <a href="{{ route('front.users.payments') }}" class="user-btn user-btn-default">Open payment history</a>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script src="{{ asset('front/assets/js/subscription-checkout.js') }}"></script>
@endpush
