@php
    $adminUser = auth()->user();
    $adminName = $adminUser ? trim($adminUser->fullName() ?: ($adminUser->email ?? 'Admin')) : 'Admin';
    $adminInitials = collect(preg_split('/\s+/', $adminName))
        ->filter()
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->take(2)
        ->implode('');
    $adminRole = $adminUser?->isAdmin() ? 'Admin' : ucfirst($adminUser->type ?? 'User');
@endphp

<nav class="navbar admin-topbar col-lg-12 col-12 p-0 d-flex flex-row">
    <div class="navbar-menu-wrapper d-flex align-items-center">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>

        <a class="admin-mobile-brand d-lg-none" href="{{ route('admin.dashboard') }}">
            <span class="sidebar-logo-icon">JG</span>
            <span class="admin-mobile-brand-text">Just Goom</span>
        </a>

        <form class="admin-search d-none d-md-block" action="{{ route('admin.users.index') }}" method="GET" role="search">
            <i class="mdi mdi-magnify"></i>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search..." autocomplete="off">
        </form>

        <ul class="navbar-nav navbar-nav-right ms-auto d-flex align-items-center">
            <li class="nav-item d-none d-lg-block">
                <a class="admin-icon-btn" href="{{ route('front.home') }}" target="_blank" rel="noopener" title="View website">
                    <i class="mdi mdi-apps"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a class="admin-icon-btn" href="#" id="adminFullscreen" title="Fullscreen">
                    <i class="mdi mdi-fullscreen"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="admin-icon-btn" href="#" id="adminThemeToggle" title="Dark / light">
                    <i class="mdi mdi-weather-night"></i>
                </a>
            </li>
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                    @if ($adminUser?->profile)
                        <img src="{{ asset($adminUser->profile) }}" alt="{{ $adminName }}" class="admin-nav-profile-img">
                    @else
                        <span class="admin-avatar">{{ $adminInitials ?: 'A' }}</span>
                    @endif
                    <span class="nav-profile-meta d-none d-md-block">
                        <span class="nav-profile-name">{{ $adminName }}</span>
                        <span class="nav-profile-role">{{ $adminRole }}</span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                        <i class="mdi mdi-account-circle-outline text-muted"></i>
                        Profile
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                        <i class="mdi mdi-cog-outline text-muted"></i>
                        Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="mdi mdi-logout text-muted"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
