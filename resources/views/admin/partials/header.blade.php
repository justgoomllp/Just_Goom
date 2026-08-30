<nav class="navbar col-lg-12 col-12 px-0 py-4 d-flex flex-row">
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <div class="navbar-brand-wrapper">
            <a class="navbar-brand brand-logo" href="{{ route('admin.dashboard') }}"><img src="{{ asset('assets/images/logo.svg') }}" alt="logo"></a>
            <a class="navbar-brand brand-logo-mini" href="{{ route('admin.dashboard') }}"><img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo"></a>
        </div>
        <h4 class="fw-bold mb-0 d-none d-md-block mt-1">Welcome back, {{ auth()->user()->name ?? 'Admin' }}</h4>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item">
                <h4 class="mb-0 fw-bold d-none d-xl-block">Mar 12, 2019 - Apr 10, 2019</h4>
            </li>
            <li class="nav-item dropdown me-1">
                <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="mdi mdi-email-open mx-0"></i>
                    <span class="count bg-info">2</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                    <p class="mb-0 fw-normal float-left dropdown-header">Messages</p>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ asset('assets/images/faces/face4.jpg') }}" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content flex-grow">
                            <h6 class="preview-subject ellipsis fw-normal">David Grey</h6>
                            <p class="fw-light small-text text-muted mb-0">The meeting is cancelled</p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ asset('assets/images/faces/face2.jpg') }}" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content flex-grow">
                            <h6 class="preview-subject ellipsis fw-normal">Tim Cook</h6>
                            <p class="fw-light small-text text-muted mb-0">New product launch</p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ asset('assets/images/faces/face3.jpg') }}" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content flex-grow">
                            <h6 class="preview-subject ellipsis fw-normal"> Johnson</h6>
                            <p class="fw-light small-text text-muted mb-0">Upcoming board meeting</p>
                        </div>
                    </a>
                </div>
            </li>
            <li class="nav-item dropdown me-2">
                <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="mdi mdi-calendar mx-0"></i>
                    <span class="count bg-danger">1</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                    <p class="mb-0 fw-normal float-left dropdown-header">Notifications</p>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-success">
                                <i class="mdi mdi-information mx-0"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject fw-normal">Application Error</h6>
                            <p class="fw-light small-text mb-0 text-muted">Just now</p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-warning">
                                <i class="mdi mdi-cog mx-0"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject fw-normal">Settings</h6>
                            <p class="fw-light small-text mb-0 text-muted">Private message</p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-info">
                                <i class="mdi mdi-account-box mx-0"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject fw-normal">New user registration</h6>
                            <p class="fw-light small-text mb-0 text-muted">2 days ago</p>
                        </div>
                    </a>
                </div>
            </li>
            <li class="nav-item nav-profile dropdown d-lg-none">
                <a class="nav-link dropdown-toggle d-flex justify-content-center align-items-center" href="#" data-bs-toggle="dropdown" id="profileDropdownMobile">
                    <img src="{{ asset('assets/images/faces/face5.jpg') }}" alt="profile" class="admin-nav-profile-img">
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdownMobile">
                    <div class="dropdown-header text-truncate">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                        <i class="mdi mdi-cog text-primary"></i>
                        Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="mdi mdi-logout text-primary"></i>
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
    <div class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center">
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                    <img src="{{ asset('assets/images/faces/face5.jpg') }}" alt="profile" class="admin-nav-profile-img">
                    <span class="nav-profile-name">{{ auth()->user()->name ?? 'Admin' }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                        <i class="mdi mdi-cog text-primary"></i>
                        Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="mdi mdi-logout text-primary"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link icon-link">
                    <i class="mdi mdi-plus-circle-outline"></i>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link icon-link">
                    <i class="mdi mdi-web"></i>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link icon-link">
                    <i class="mdi mdi-clock-outline"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>
