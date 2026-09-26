@extends('admin.layouts.app')

@section('title', 'Advertisements')
@section('page-title', 'Advertisements')
@section('page-subtitle', 'Schedule and manage homepage and sidebar banners.')
@section('page-action')
    <a href="{{ route('admin.advertisements.create') }}" class="btn btn-primary">
        <i class="mdi mdi-plus"></i>
        Add Advertisement
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form class="admin-listing-filters" id="advertisementsFilters">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="filter_position">Position</label>
                                <select name="position" id="filter_position" class="form-control">
                                    <option value="">All positions</option>
                                    <option value="homepage" @selected(request('position') === 'homepage')>Homepage</option>
                                    <option value="sidebar" @selected(request('position') === 'sidebar')>Sidebar</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="filter_is_active">Status</label>
                                <select name="is_active" id="filter_is_active" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="1" @selected(request('is_active') === '1')>Active</option>
                                    <option value="0" @selected(request('is_active') === '0')>Inactive</option>
                                </select>
                            </div>
                            @include('admin.partials.listing-filter-actions')
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="advertisementsTable" class="table admin-datatable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Banner</th>
                                    <th>Title</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Priority</th>
                                    <th>Period</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('admin.partials.datatable-assets')

@push('scripts')
    <script>
        initAdminDataTable('#advertisementsTable', {
            url: @json(route('admin.advertisements.datatable')),
            filters: '#advertisementsFilters',
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, width: '50px' },
                { data: 'banner', orderable: false, searchable: false },
                { data: 'title' },
                { data: 'name', orderable: false },
                { data: 'email', orderable: false },
                { data: 'position' },
                { data: 'priority' },
                { data: 'period' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false }
            ]
        });
    </script>
@endpush
