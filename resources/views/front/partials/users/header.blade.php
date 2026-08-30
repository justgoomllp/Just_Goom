@php
  $user = auth()->user();
  $displayName = $user?->companyProfile?->company_name ?? $user?->fullName() ?? 'User';
  $nameParts = preg_split('/\s+/', trim($displayName), -1, PREG_SPLIT_NO_EMPTY);
  $initials = count($nameParts) >= 2
    ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
    : strtoupper(substr($displayName, 0, 2));
@endphp
<header class="user-page-header">
  <div class="user-page-header-left">
    <button type="button" class="user-menu-btn" aria-label="Menu">☰</button>
    <h1 class="user-greeting"><strong data-user-name>{{ $displayName }}</strong></h1>
  </div>
  <div class="user-page-header-end">
    <div class="user-page-header-actions">
      <button type="button" class="user-theme-btn" aria-label="Toggle dark mode" title="Toggle dark mode">🌙</button>
      <span class="user-plan-chip">{{ !empty($hasActivePlan) ? ($activeUserPlan?->plan?->name ?? 'Active') : 'No Plan' }}</span>
      <div class="user-header-dropdown">
        <button type="button" class="user-header-dropdown-toggle" aria-label="Account menu" aria-expanded="false" aria-haspopup="true">
          <span class="user-topbar-avatar">{{ $initials }}</span>
          <span class="user-header-dropdown-caret" aria-hidden="true">▾</span>
        </button>
        <div class="user-header-dropdown-menu" role="menu">
          <div class="user-header-dropdown-user">
            <strong data-user-name>{{ $displayName }}</strong>
            <span>{{ $user?->email }}</span>
          </div>
          <a href="{{ route('front.users.profile') }}" class="user-header-dropdown-item" role="menuitem">
            <span class="user-header-dropdown-icon" aria-hidden="true">👤</span>
            My Profile
          </a>
          <div class="user-header-dropdown-divider" role="separator"></div>
          <form method="POST" action="{{ route('front.logout') }}" class="user-header-dropdown-form">
            @csrf
            <button type="submit" class="user-header-dropdown-item user-header-dropdown-logout" role="menuitem">
              <span class="user-header-dropdown-icon" aria-hidden="true">🚪</span>
              Logout
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>
