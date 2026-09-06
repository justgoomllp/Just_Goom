@extends('admin.layouts.app')

@section('title', 'Users')
@section('page-title', 'Users')
@section('page-subtitle', 'Manage admin, agent, and platform accounts.')
@section('page-action')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="mdi mdi-plus"></i>
        Add User
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

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form class="admin-listing-filters" id="usersFilters">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="filter_q">Search</label>
                                <input type="search" name="q" id="filter_q" class="form-control" placeholder="Name, email, phone, referral">
                            </div>
                            <div class="col-md-2">
                                <label for="filter_type">Type</label>
                                <select name="type" id="filter_type" class="form-control">
                                    <option value="">All types</option>
                                    <option value="user">User</option>
                                    <option value="agent">Agent</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="filter_status">Status</label>
                                <select name="status" id="filter_status" class="form-control">
                                    <option value="">All statuses</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                    <option value="2">Suspended</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="filter_email_verified">Email</label>
                                <select name="email_verified" id="filter_email_verified" class="form-control">
                                    <option value="">All</option>
                                    <option value="1">Verified</option>
                                    <option value="0">Pending</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="filter_category_id">Category</label>
                                <select name="category_id" id="filter_category_id" class="form-control">
                                    <option value="">All categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-auto admin-listing-filter-actions">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="usersTable" class="table admin-datatable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Referral Code</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Email Verified</th>
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
        initAdminDataTable('#usersTable', {
            url: @json(route('admin.users.datatable')),
            filters: '#usersFilters',
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, width: '50px' },
                { data: 'name' },
                { data: 'email' },
                { data: 'type' },
                { data: 'referral_code' },
                { data: 'category', orderable: false },
                { data: 'status', orderable: false, searchable: false },
                { data: 'email_verified', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false }
            ]
        });
    </script>
@endpush
