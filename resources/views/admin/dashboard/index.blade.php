@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Welcome back, Admin')

@section('content')
    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title text-md-center text-xl-left">Categories</p>
                    <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                        <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">0</h3>
                        <i class="mdi mdi-shape icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                    </div>
                    <p class="mb-0 mt-2 text-muted">Ready to manage catalog categories</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title text-md-center text-xl-left">Sub Categories</p>
                    <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                        <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">0</h3>
                        <i class="mdi mdi-shape-plus icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                    </div>
                    <p class="mb-0 mt-2 text-muted">Organize sub-category records</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title text-md-center text-xl-left">Users</p>
                    <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                        <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">0</h3>
                        <i class="mdi mdi-account-group icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                    </div>
                    <p class="mb-0 mt-2 text-muted">Admin users and permissions</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Admin Content</h4>
                    <p class="card-description">This page is now using the vertical default light theme layout.</p>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Header</td>
                                    <td><label class="badge badge-success">Integrated</label></td>
                                    <td>Blade partial</td>
                                </tr>
                                <tr>
                                    <td>Sidebar</td>
                                    <td><label class="badge badge-success">Integrated</label></td>
                                    <td>Blade partial</td>
                                </tr>
                                <tr>
                                    <td>Footer</td>
                                    <td><label class="badge badge-success">Integrated</label></td>
                                    <td>Blade partial</td>
                                </tr>
                                <tr>
                                    <td>Content</td>
                                    <td><label class="badge badge-info">Ready</label></td>
                                    <td>@yield('page-title', 'Dashboard')</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
