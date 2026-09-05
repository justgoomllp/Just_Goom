@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'CRM')

@php
    $userTrend = $stats['usersLastMonth'] > 0
        ? round((($stats['usersThisMonth'] - $stats['usersLastMonth']) / $stats['usersLastMonth']) * 100, 1)
        : ($stats['usersThisMonth'] > 0 ? 100 : 0);
    $membersPct = $userMix['members'];
    $agentsPct = $userMix['agents'];
    $adminsPct = max(0, 100 - $membersPct - $agentsPct);
    $donut = "conic-gradient(#0ab39c 0 {$membersPct}%, #f7b84b {$membersPct}% ".($membersPct + $agentsPct)."%, #405189 ".($membersPct + $agentsPct)."% 100%)";
@endphp

@section('content')
    <div class="row">
        <div class="col-sm-6 col-xl grid-margin stretch-card">
            <div class="card admin-kpi">
                <div class="card-body">
                    <div>
                        <p class="admin-kpi-label">Categories</p>
                        <h3 class="admin-kpi-value">{{ $stats['categories'] }}</h3>
                        <p class="admin-kpi-trend">Catalog groups</p>
                    </div>
                    <span class="admin-kpi-icon primary"><i class="mdi mdi-rocket-launch-outline"></i></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl grid-margin stretch-card">
            <div class="card admin-kpi">
                <div class="card-body">
                    <div>
                        <p class="admin-kpi-label">Sub Categories</p>
                        <h3 class="admin-kpi-value">{{ $stats['subCategories'] }}</h3>
                        <p class="admin-kpi-trend">Nested catalog items</p>
                    </div>
                    <span class="admin-kpi-icon success"><i class="mdi mdi-cash-sync"></i></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl grid-margin stretch-card">
            <div class="card admin-kpi">
                <div class="card-body">
                    <div>
                        <p class="admin-kpi-label">Users</p>
                        <h3 class="admin-kpi-value">{{ $stats['users'] }}</h3>
                        <p class="admin-kpi-trend">
                            @if ($userTrend >= 0)
                                <span class="up"><i class="mdi mdi-arrow-up"></i> {{ $userTrend }}%</span>
                            @else
                                <span class="down"><i class="mdi mdi-arrow-down"></i> {{ abs($userTrend) }}%</span>
                            @endif
                            vs last month
                        </p>
                    </div>
                    <span class="admin-kpi-icon warning"><i class="mdi mdi-pulse"></i></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl grid-margin stretch-card">
            <div class="card admin-kpi">
                <div class="card-body">
                    <div>
                        <p class="admin-kpi-label">Advertisements</p>
                        <h3 class="admin-kpi-value">{{ $stats['advertisements'] }}</h3>
                        <p class="admin-kpi-trend">Campaign banners</p>
                    </div>
                    <span class="admin-kpi-icon info"><i class="mdi mdi-trophy-outline"></i></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl grid-margin stretch-card">
            <div class="card admin-kpi">
                <div class="card-body">
                    <div>
                        <p class="admin-kpi-label">Active Ads</p>
                        <h3 class="admin-kpi-value">{{ $stats['activeAds'] }}</h3>
                        <p class="admin-kpi-trend">Live in date range</p>
                    </div>
                    <span class="admin-kpi-icon danger"><i class="mdi mdi-heart-outline"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 grid-margin stretch-card">
            <div class="card w-100">
                <div class="card-header">
                    <h4 class="card-title">Catalog Forecast</h4>
                    <span class="card-header-muted">By module</span>
                </div>
                <div class="card-body">
                    <div class="admin-bars">
                        <div class="admin-bar-col">
                            <div class="admin-bar-track">
                                <div class="admin-bar primary" style="height: {{ max($bars['categories'], 4) }}%"></div>
                            </div>
                            <span>Categories</span>
                        </div>
                        <div class="admin-bar-col">
                            <div class="admin-bar-track">
                                <div class="admin-bar success" style="height: {{ max($bars['subCategories'], 4) }}%"></div>
                            </div>
                            <span>Sub Categories</span>
                        </div>
                        <div class="admin-bar-col">
                            <div class="admin-bar-track">
                                <div class="admin-bar warning" style="height: {{ max($bars['advertisements'], 4) }}%"></div>
                            </div>
                            <span>Ads</span>
                        </div>
                    </div>
                    <div class="admin-legend">
                        <span><i style="background:#6559cc"></i> Categories {{ $stats['categories'] }}</span>
                        <span><i style="background:#0ab39c"></i> Sub Categories {{ $stats['subCategories'] }}</span>
                        <span><i style="background:#f7b84b"></i> Ads {{ $stats['advertisements'] }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 grid-margin stretch-card">
            <div class="card w-100">
                <div class="card-header">
                    <h4 class="card-title">User Type</h4>
                    <span class="card-header-muted">Account mix</span>
                </div>
                <div class="card-body">
                    <div class="admin-donut-wrap">
                        <div class="admin-donut" style="background: {{ $donut }};" data-center="{{ $stats['users'] }} users"></div>
                        <div class="admin-legend" style="flex-direction: column;">
                            <span><i style="background:#0ab39c"></i> Users {{ $stats['members'] }} ({{ $membersPct }}%)</span>
                            <span><i style="background:#f7b84b"></i> Agents {{ $stats['agents'] }} ({{ $agentsPct }}%)</span>
                            <span><i style="background:#405189"></i> Admins {{ $stats['admins'] }} ({{ $adminsPct }}%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 grid-margin stretch-card">
            <div class="card w-100">
                <div class="card-header">
                    <h4 class="card-title">Balance Overview</h4>
                    <span class="card-header-muted">New users</span>
                </div>
                <div class="card-body">
                    <div class="admin-overview-metrics">
                        <div>
                            <strong>{{ $stats['usersThisMonth'] }}</strong>
                            <span>This month</span>
                        </div>
                        <div>
                            <strong>{{ $stats['usersLastMonth'] }}</strong>
                            <span>Last month</span>
                        </div>
                        <div>
                            <strong>{{ $stats['users'] }}</strong>
                            <span>Total users</span>
                        </div>
                    </div>
                    <svg class="admin-area" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                        <polyline fill="none" stroke="#0ab39c" stroke-width="2" points="{{ $areaPoints }}"></polyline>
                        <polyline fill="rgba(10,179,156,0.12)" stroke="none" points="0,100 {{ $areaPoints }} 100,100"></polyline>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 grid-margin stretch-card">
            <div class="card w-100">
                <div class="card-header">
                    <h4 class="card-title">Recent Users</h4>
                    <span class="card-header-muted">{{ now()->format('d M Y') }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="admin-user-chip">
                                                @if ($user->profile)
                                                    <img src="{{ asset($user->profile) }}" alt="{{ $user->fullName() }}" class="admin-avatar">
                                                @else
                                                    <span class="admin-avatar">{{ mb_strtoupper(mb_substr($user->fullName() ?: $user->email, 0, 1)) }}</span>
                                                @endif
                                                <div>
                                                    <strong>{{ $user->fullName() ?: $user->email }}</strong>
                                                    <small>{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($user->type === 'admin')
                                                <label class="badge badge-info">Admin</label>
                                            @elseif ($user->type === 'agent')
                                                <label class="badge badge-primary">Agent</label>
                                            @else
                                                <label class="badge badge-secondary">User</label>
                                            @endif
                                        </td>
                                        <td>
                                            @include('admin.partials.status-toggle', [
                                                'action' => route('admin.users.status', $user),
                                                'active' => (int) $user->status === 1,
                                                'suspended' => (int) $user->status === 2,
                                                'disabled' => auth()->id() === $user->id,
                                                'disabledTitle' => 'You cannot change your own status',
                                            ])
                                        </td>
                                        <td>{{ $user->created_at?->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No users yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 grid-margin stretch-card">
            <div class="card w-100">
                <div class="card-header">
                    <h4 class="card-title">My Tasks</h4>
                    <a href="{{ route('admin.settings.index') }}" class="card-header-muted">Settings</a>
                </div>
                <div class="card-body">
                    <a class="admin-task" href="{{ route('admin.categories.create') }}">
                        <span class="admin-task-check"></span>
                        <div>
                            Add a new category
                            <small>Keep the catalog up to date</small>
                        </div>
                    </a>
                    <a class="admin-task" href="{{ route('admin.users.index') }}">
                        <span class="admin-task-check"></span>
                        <div>
                            Review user accounts
                            <small>{{ $stats['users'] }} accounts on the platform</small>
                        </div>
                    </a>
                    <a class="admin-task" href="{{ route('admin.advertisements.create') }}">
                        <span class="admin-task-check"></span>
                        <div>
                            Publish an advertisement
                            <small>{{ $stats['activeAds'] }} campaigns currently live</small>
                        </div>
                    </a>
                    <a class="admin-task" href="{{ route('admin.sub-categories.create') }}">
                        <span class="admin-task-check"></span>
                        <div>
                            Organize sub categories
                            <small>Nest items under a parent category</small>
                        </div>
                    </a>
                    <a class="admin-task" href="{{ route('admin.settings.index') }}">
                        <span class="admin-task-check"></span>
                        <div>
                            Check admin settings
                            <small>Workspace preferences</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
