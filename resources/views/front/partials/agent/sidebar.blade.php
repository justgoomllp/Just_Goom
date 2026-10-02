@php
  $sidebarUser = auth()->user();
@endphp
<button type="button" class="user-sidebar-close" aria-label="Close menu">✕</button>
<div class="user-sidebar-brand">
  <a href="{{ route('front.agent.dashboard') }}"><img src="{{ asset('front/assets/images/justgoom-logo.png') }}" alt="JustGoom"></a>
</div>
<div class="user-sidebar-plan">
  <span class="user-sidebar-plan-icon">🤝</span>
  <div>
    <strong>Agent portal</strong>
    <span>{{ $sidebarUser?->referral_code ? 'Code '.$sidebarUser->referral_code : 'Commission partner' }}</span>
  </div>
</div>
<nav>
  <div class="user-nav-section">
    <div class="user-nav-heading">Overview</div>
    <a href="{{ route('front.agent.dashboard') }}" class="user-nav-link{{ request()->routeIs('front.agent.dashboard') ? ' active' : '' }}"><span class="nav-icon">📊</span>Dashboard</a>
    <a href="{{ route('front.agent.customers') }}" class="user-nav-link{{ request()->routeIs('front.agent.customers', 'front.agent.customers.show') ? ' active' : '' }}"><span class="nav-icon">👥</span>Customers</a>
    <a href="{{ route('front.agent.earnings') }}" class="user-nav-link{{ request()->routeIs('front.agent.earnings') ? ' active' : '' }}"><span class="nav-icon">💰</span>Earnings</a>
  </div>
  <div class="user-nav-section">
    <div class="user-nav-heading">Account</div>
    <a href="{{ route('front.agent.change-password') }}" class="user-nav-link{{ request()->routeIs('front.agent.change-password') ? ' active' : '' }}"><span class="nav-icon">🔑</span>Change Password</a>
  </div>
</nav>
<div class="user-sidebar-footer">
  <a href="{{ route('front.home') }}">🌐 View Public Site</a>
  <form method="POST" action="{{ route('front.logout') }}" id="frontLogoutForm" style="display:block;">
    @csrf
    <button type="submit" class="user-logout-btn" style="color: #fff;">🚪 Logout</button>
  </form>
</div>
