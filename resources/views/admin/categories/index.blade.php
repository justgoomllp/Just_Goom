@extends('admin.layouts.app')

@section('title', 'Categories')
@section('page-title', 'Categories')
@section('page-subtitle', 'Organize the catalog groups shown across the site.')
@section('page-action')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="mdi mdi-plus"></i>
        Add Category
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

                    <form class="admin-listing-filters" id="categoriesFilters">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="filter_q">Search</label>
                                <input type="search" name="q" id="filter_q" class="form-control" placeholder="Name or slug">
                            </div>
                            <div class="col-md-3">
                                <label for="filter_status">Status</label>
                                <select name="status" id="filter_status" class="form-control">
                                    <option value="">All statuses</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3 admin-listing-filter-actions">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="categoriesTable" class="table admin-datatable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Icon</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
        initAdminDataTable('#categoriesTable', {
            url: @json(route('admin.categories.datatable')),
            filters: '#categoriesFilters',
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, width: '50px' },
                { data: 'name' },
                { data: 'slug' },
                { data: 'icon', orderable: false, searchable: false },
                { data: 'status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false }
            ]
        });
    </script>
@endpush
