@extends('admin.layouts.app')

@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Users</h4>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-plus"></i>
                            Add User
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Phone</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Email Verified</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $users->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($user->profile)
                                                    <img src="{{ asset($user->profile) }}" alt="{{ $user->fullName() }}" width="36" height="36" class="rounded-circle border me-2" style="object-fit: cover;">
                                                @endif
                                                {{ $user->fullName() }}
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->type === 'admin')
                                                <label class="badge badge-info">Admin</label>
                                            @elseif ($user->type === 'agent')
                                                <label class="badge badge-primary">Agent</label>
                                            @else
                                                <label class="badge badge-secondary">User</label>
                                            @endif
                                        </td>
                                        <td>{{ $user->phone ?: '-' }}</td>
                                        <td>{{ $user->category->name ?? '-' }}</td>
                                        <td>
                                            @if ($user->status === 1)
                                                <label class="badge badge-success">Active</label>
                                            @elseif ($user->status === 2)
                                                <label class="badge badge-warning">Suspended</label>
                                            @else
                                                <label class="badge badge-danger">Inactive</label>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->hasVerifiedEmail())
                                                <label class="badge badge-success">Verified</label>
                                            @else
                                                <label class="badge badge-warning">Pending</label>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline delete-user-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('vendor-scripts')
    <script src="{{ asset('assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
@endpush

@push('scripts')
    <script>
        (function () {
            var forms = document.querySelectorAll('.delete-user-form');

            forms.forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    swal({
                        title: 'Delete user?',
                        text: 'This user will be removed.',
                        icon: 'warning',
                        buttons: {
                            cancel: {
                                text: 'Cancel',
                                visible: true,
                                closeModal: true
                            },
                            confirm: {
                                text: 'Delete',
                                value: true,
                                visible: true,
                                closeModal: true
                            }
                        },
                        dangerMode: true
                    }).then(function (willDelete) {
                        if (willDelete) {
                            form.submit();
                        }
                    });
                });
            });
        })();
    </script>
@endpush
