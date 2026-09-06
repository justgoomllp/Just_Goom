@extends('admin.layouts.app')

@section('title', 'Sub Categories')
@section('page-title', 'Sub Categories')
@section('page-subtitle', 'Nest catalog items under a parent category.')
@section('page-action')
    <a href="{{ route('admin.sub-categories.create') }}" class="btn btn-primary">
        <i class="mdi mdi-plus"></i>
        Add Sub Category
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

                    <form class="admin-listing-filters" id="subCategoriesFilters">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="filter_q">Search</label>
                                <input type="search" name="q" id="filter_q" class="form-control" placeholder="Name, slug, or category">
                            </div>
                            <div class="col-md-3">
                                <label for="filter_category_id">Category</label>
                                <select name="category_id" id="filter_category_id" class="form-control">
                                    <option value="">All categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
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
                        <table id="subCategoriesTable" class="table admin-datatable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
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
        initAdminDataTable('#subCategoriesTable', {
            url: @json(route('admin.sub-categories.datatable')),
            filters: '#subCategoriesFilters',
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, width: '50px' },
                { data: 'category', orderable: false },
                { data: 'name' },
                { data: 'slug' },
                { data: 'icon', orderable: false, searchable: false },
                { data: 'status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false }
            ]
        });
    </script>
@endpush
