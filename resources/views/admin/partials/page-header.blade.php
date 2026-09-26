<div class="admin-page-header">
    <h1 class="admin-page-title">@yield('page-title', 'Dashboard')</h1>
    <div class="admin-page-header-right">
        <ol class="admin-breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
            <li>
                @if (request()->routeIs('admin.dashboard'))
                    Dashboard
                @else
                    @yield('page-title', 'Dashboard')
                @endif
            </li>
        </ol>
        @yield('page-action')
    </div>
</div>
