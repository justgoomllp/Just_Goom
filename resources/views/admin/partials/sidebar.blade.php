<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
        <span class="sidebar-logo-icon">JG</span>
        <span class="sidebar-logo-text">Just Goom</span>
    </a>
    <ul class="nav">
        <li class="nav-item sidebar-category">
            <p>Menu</p>
        </li>
        @if (!empty($adminModules['dashboard']))
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="mdi mdi-view-dashboard-outline menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        @endif

        @if (!empty($adminModules['categories']) || !empty($adminModules['sub-categories']) || !empty($adminModules['advertisements']))
        <li class="nav-item sidebar-category">
            <p>Pages</p>
        </li>
        @endif
        @if (!empty($adminModules['categories']))
        <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                <i class="mdi mdi-shape-outline menu-icon"></i>
                <span class="menu-title">Categories</span>
            </a>
        </li>
        @endif
        @if (!empty($adminModules['sub-categories']))
        <li class="nav-item {{ request()->routeIs('admin.sub-categories.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.sub-categories.*') ? 'active' : '' }}" href="{{ route('admin.sub-categories.index') }}">
                <i class="mdi mdi-file-tree menu-icon"></i>
                <span class="menu-title">Sub Categories</span>
            </a>
        </li>
        @endif
        @if (!empty($adminModules['advertisements']))
        <li class="nav-item {{ request()->routeIs('admin.advertisements.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.advertisements.*') ? 'active' : '' }}" href="{{ route('admin.advertisements.index') }}">
                <i class="mdi mdi-bullhorn-outline menu-icon"></i>
                <span class="menu-title">Advertisements</span>
            </a>
        </li>
        @endif

        @if (!empty($adminModules['users']) || !empty($adminModules['commission']) || !empty($adminModules['notifications']) || !empty($adminModules['settings']))
        <li class="nav-item sidebar-category">
            <p>Components</p>
        </li>
        @endif
        @if (!empty($adminModules['users']))
        <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="mdi mdi-account-circle-outline menu-icon"></i>
                <span class="menu-title">Users</span>
            </a>
        </li>
        @endif
        @if (!empty($adminModules['commission']))
        <li class="nav-item {{ request()->routeIs('admin.commission.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.commission.*') ? 'active' : '' }}" href="{{ route('admin.commission.index') }}">
                <i class="mdi mdi-percent menu-icon"></i>
                <span class="menu-title">Commission</span>
            </a>
        </li>
        @endif
        @if (!empty($adminModules['notifications']))
        <li class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}" href="{{ route('admin.notifications.index') }}">
                <i class="mdi mdi-bell-outline menu-icon"></i>
                <span class="menu-title">Notifications</span>
            </a>
        </li>
        @endif
        @if (!empty($adminModules['settings']))
        <li class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                <i class="mdi mdi-cog-outline menu-icon"></i>
                <span class="menu-title">Settings</span>
            </a>
        </li>
        @endif
    </ul>
</nav>
