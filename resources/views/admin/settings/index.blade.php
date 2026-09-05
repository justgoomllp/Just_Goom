@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <span class="admin-kpi-icon primary mb-3"><i class="mdi mdi-office-building-outline"></i></span>
                    <h4 class="card-title mb-2">Workspace</h4>
                    <p class="text-muted mb-0">Brand, company, and general admin settings will live here.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <span class="admin-kpi-icon warning mb-3"><i class="mdi mdi-shield-check-outline"></i></span>
                    <h4 class="card-title mb-2">Access</h4>
                    <p class="text-muted mb-0">Role and permission controls can be added to this section.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <span class="admin-kpi-icon success mb-3"><i class="mdi mdi-bell-outline"></i></span>
                    <h4 class="card-title mb-2">Notifications</h4>
                    <p class="text-muted mb-0">Email and alert preferences will be managed from here.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
