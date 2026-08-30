@php
  $sidebarUser = auth()->user();
  if ($sidebarUser) {
      $sidebarUser->loadMissing('category');
  }
  $hasActivePlan = $hasActivePlan ?? false;
  $sidebarUserPlan = $activeUserPlan ?? ($sidebarUser
    ? \App\Models\UserPlan::with('plan')
        ->where('user_id', auth()->id())
        ->where('next_purchase_date', '>=', now()->toDateString())
        ->orderByDesc('next_purchase_date')
        ->first()
    : null);
  $sidebarPlanName = $hasActivePlan
    ? ($sidebarUserPlan?->plan?->name ?? 'Active')
    : 'No Plan';
  $sidebarProjectSection = \App\Support\ProjectSection::forUser($sidebarUser);
  $sidebarProjectLabel = match ($sidebarProjectSection) {
      \App\Support\ProjectSection::REAL_ESTATE => 'My Listings',
      \App\Support\ProjectSection::ENGINEERING => 'My Listings',
      \App\Support\ProjectSection::ECOMMERCE => 'My Products',
      default => 'My Projects',
  };
  $sidebarProjectIcon = match ($sidebarProjectSection) {
      \App\Support\ProjectSection::REAL_ESTATE => '🏠',
      \App\Support\ProjectSection::ENGINEERING => '⚙️',
      \App\Support\ProjectSection::ECOMMERCE => '🛍️',
      default => '📁',
  };
  $sidebarPlanIcon = match ($sidebarPlanName) {
      'Platinum' => '💎',
      'Gold' => '🥇',
      'Silver' => '🥈',
      'No Plan' => '🔒',
      default => '🆓',
  };
  $lockHref = route('front.users.subscription');
@endphp
<button type="button" class="user-sidebar-close" aria-label="Close menu">✕</button>
<div class="user-sidebar-brand">
  <a href="{{ $hasActivePlan ? route('front.users.dashboard') : route('front.users.profile') }}"><img src="{{ asset('front/assets/images/justgoom-logo.png') }}" alt="JustGoom"></a>
</div>
<div class="user-sidebar-plan">
  <span class="user-sidebar-plan-icon">{{ $sidebarPlanIcon }}</span>
  <div>
    <strong>{{ $sidebarPlanName }}{{ $sidebarPlanName === 'No Plan' ? '' : ' Plan' }}</strong>
    <span><a href="{{ route('front.users.subscription') }}" style="color:inherit;text-decoration:underline;">{{ $hasActivePlan ? 'Manage subscription' : 'Purchase a plan' }}</a></span>
  </div>
</div>
<nav>
  <div class="user-nav-section">
    <div class="user-nav-heading">Account</div>
    <a href="{{ route('front.users.profile') }}" class="user-nav-link{{ request()->routeIs('front.users.profile*') ? ' active' : '' }}" data-nav="profile"><span class="nav-icon">👤</span>My Profile</a>
    <a href="{{ route('front.users.subscription') }}" class="user-nav-link{{ request()->routeIs('front.users.subscription') ? ' active' : '' }}" data-nav="subscription"><span class="nav-icon">💳</span>Subscription</a>
    <a href="{{ route('front.users.payments') }}" class="user-nav-link{{ request()->routeIs('front.users.payments*') ? ' active' : '' }}" data-nav="payments"><span class="nav-icon">🧾</span>Payment History</a>
    <a href="{{ route('front.users.audit-logs') }}" class="user-nav-link{{ request()->routeIs('front.users.audit-logs') ? ' active' : '' }}" data-nav="audit-logs"><span class="nav-icon">📋</span>Activity Log</a>
    <a href="{{ route('front.users.change-password') }}" class="user-nav-link{{ request()->routeIs('front.users.change-password') ? ' active' : '' }}" data-nav="change-password"><span class="nav-icon">🔑</span>Change Password</a>
  </div>
  <div class="user-nav-section">
    <div class="user-nav-heading">Overview</div>
    <a href="{{ $hasActivePlan ? route('front.users.dashboard') : $lockHref }}" class="user-nav-link{{ request()->routeIs('front.users.dashboard') ? ' active' : '' }}{{ $hasActivePlan ? '' : ' is-locked' }}" data-nav="dashboard"@if(! $hasActivePlan) data-requires-plan="1"@endif><span class="nav-icon">📊</span>Dashboard</a>
    <a href="{{ $hasActivePlan ? route('front.users.business-activity') : $lockHref }}" class="user-nav-link{{ request()->routeIs('front.users.business-activity') ? ' active' : '' }}{{ $hasActivePlan ? '' : ' is-locked' }}" data-nav="business-activity"@if(! $hasActivePlan) data-requires-plan="1"@endif><span class="nav-icon">📈</span>Business Activity</a>
  </div>
  <div class="user-nav-section">
    <div class="user-nav-heading">My Business</div>
    <a href="{{ $hasActivePlan ? route('front.users.team') : $lockHref }}" class="user-nav-link{{ request()->routeIs('front.users.team', 'front.users.team-*') ? ' active' : '' }}{{ $hasActivePlan ? '' : ' is-locked' }}" data-nav="team"@if(! $hasActivePlan) data-requires-plan="1"@endif><span class="nav-icon">👥</span>My Team</a>
    <a href="{{ $hasActivePlan ? route('front.users.services') : $lockHref }}" class="user-nav-link{{ request()->routeIs('front.users.services', 'front.users.service-*') ? ' active' : '' }}{{ $hasActivePlan ? '' : ' is-locked' }}" data-nav="services"@if(! $hasActivePlan) data-requires-plan="1"@endif><span class="nav-icon">💼</span>Services & Products</a>
    <a href="{{ $hasActivePlan ? route('front.users.documents') : $lockHref }}" class="user-nav-link{{ request()->routeIs('front.users.documents', 'front.users.document-*') ? ' active' : '' }}{{ $hasActivePlan ? '' : ' is-locked' }}" data-nav="documents"@if(! $hasActivePlan) data-requires-plan="1"@endif><span class="nav-icon">📄</span>My Documents</a>
    <a href="{{ $hasActivePlan ? route('front.users.projects') : $lockHref }}" class="user-nav-link{{ request()->routeIs('front.users.projects', 'front.users.project-*') ? ' active' : '' }}{{ $hasActivePlan ? '' : ' is-locked' }}" data-nav="projects"@if(! $hasActivePlan) data-requires-plan="1"@endif><span class="nav-icon">{{ $sidebarProjectIcon }}</span>{{ $sidebarProjectLabel }}</a>
  </div>
  <div class="user-nav-section">
    <div class="user-nav-heading">Content & Marketing</div>
    <a href="{{ $hasActivePlan ? route('front.users.articles') : $lockHref }}" class="user-nav-link{{ request()->routeIs('front.users.articles', 'front.users.article-*') ? ' active' : '' }}{{ $hasActivePlan ? '' : ' is-locked' }}" data-nav="articles"@if(! $hasActivePlan) data-requires-plan="1"@endif><span class="nav-icon">📝</span>My Articles</a>
    <a href="{{ $hasActivePlan ? route('front.users.videos') : $lockHref }}" class="user-nav-link{{ request()->routeIs('front.users.videos', 'front.users.video-*') ? ' active' : '' }}{{ $hasActivePlan ? '' : ' is-locked' }}" data-nav="videos"@if(! $hasActivePlan) data-requires-plan="1"@endif><span class="nav-icon">🎬</span>My Videos</a>
    <a href="{{ $hasActivePlan ? route('front.users.offers') : $lockHref }}" class="user-nav-link{{ request()->routeIs('front.users.offers', 'front.users.offer-*') ? ' active' : '' }}{{ $hasActivePlan ? '' : ' is-locked' }}" data-nav="offers"@if(! $hasActivePlan) data-requires-plan="1"@endif><span class="nav-icon">🏷️</span>My Offers</a>
  </div>
  <div class="user-nav-section">
    <div class="user-nav-heading">Engagement</div>
    <a href="{{ $hasActivePlan ? route('front.users.inquiries') : $lockHref }}" class="user-nav-link{{ request()->routeIs('front.users.inquiries', 'front.users.inquiries.*') ? ' active' : '' }}{{ $hasActivePlan ? '' : ' is-locked' }}" data-nav="inquiries"@if(! $hasActivePlan) data-requires-plan="1"@endif><span class="nav-icon">💬</span>My Inquiries</a>
    <a href="{{ $hasActivePlan ? route('front.users.notifications') : $lockHref }}" class="user-nav-link{{ request()->routeIs('front.users.notifications', 'front.users.notifications.*') ? ' active' : '' }}{{ $hasActivePlan ? '' : ' is-locked' }}" data-nav="notifications"@if(! $hasActivePlan) data-requires-plan="1"@endif><span class="nav-icon">✉</span>Notifications</a>
  </div>
</nav>
<div class="user-sidebar-footer">
  <a href="{{ route('front.home') }}">🌐 View Public Site</a>
  <form method="POST" action="{{ route('front.logout') }}" id="frontLogoutForm" style="display:block;">
    @csrf
    <button type="submit" class="user-logout-btn" style="color: #fff;">🚪 Logout</button>
  </form>
</div>
