<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item sidebar-category">
            <p>Navigation</p>
            <span></span>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="mdi mdi-view-quilt menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item sidebar-category">
            <p>Catalog</p>
            <span></span>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                <i class="mdi mdi-shape menu-icon"></i>
                <span class="menu-title">Categories</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.sub-categories.*') ? 'active' : '' }}" href="{{ route('admin.sub-categories.index') }}">
                <i class="mdi mdi-shape-plus menu-icon"></i>
                <span class="menu-title">Sub Categories</span>
            </a>
        </li>

        <li class="nav-item sidebar-category">
            <p>Marketing</p>
            <span></span>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.advertisements.*') ? 'active' : '' }}" href="{{ route('admin.advertisements.index') }}">
                <i class="mdi mdi-billboard menu-icon"></i>
                <span class="menu-title">Advertisements</span>
            </a>
        </li>

        <li class="nav-item sidebar-category">
            <p>System</p>
            <span></span>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="mdi mdi-account-cog menu-icon"></i>
                <span class="menu-title">Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                <i class="mdi mdi-cog-outline menu-icon"></i>
                <span class="menu-title">Settings</span>
            </a>
        </li>
    </ul>
</nav>
