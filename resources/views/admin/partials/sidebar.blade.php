<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
        <span class="sidebar-logo-icon">JG</span>
        <span class="sidebar-logo-text">Just Goom</span>
    </a>
    <ul class="nav">
        <li class="nav-item sidebar-category">
            <p>Menu</p>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="mdi mdi-view-dashboard-outline menu-icon"></i>
                <span class="menu-title">Dashboards</span>
            </a>
        </li>

        <li class="nav-item sidebar-category">
            <p>Pages</p>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                <i class="mdi mdi-shape-outline menu-icon"></i>
                <span class="menu-title">Categories</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.sub-categories.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.sub-categories.*') ? 'active' : '' }}" href="{{ route('admin.sub-categories.index') }}">
                <i class="mdi mdi-file-tree menu-icon"></i>
                <span class="menu-title">Sub Categories</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.advertisements.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.advertisements.*') ? 'active' : '' }}" href="{{ route('admin.advertisements.index') }}">
                <i class="mdi mdi-bullhorn-outline menu-icon"></i>
                <span class="menu-title">Advertisements</span>
            </a>
        </li>

        <li class="nav-item sidebar-category">
            <p>Components</p>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="mdi mdi-account-circle-outline menu-icon"></i>
                <span class="menu-title">Users</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                <i class="mdi mdi-cog-outline menu-icon"></i>
                <span class="menu-title">Settings</span>
            </a>
        </li>
    </ul>
</nav>
