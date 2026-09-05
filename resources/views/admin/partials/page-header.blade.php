<div class="admin-page-header">
    <h1 class="admin-page-title">@yield('page-title', 'Dashboard')</h1>
    <div class="admin-page-header-right">
        <ol class="admin-breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
            <li>@yield('page-title', 'Dashboard')</li>
        </ol>
        @yield('page-action')
    </div>
</div>
