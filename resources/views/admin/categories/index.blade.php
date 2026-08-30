@extends('admin.layouts.app')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Categories</h4>
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-plus"></i>
                            Add Category
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table">
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
                            <tbody>
                                @forelse ($categories as $category)
                                    <tr>
                                        <td>{{ $categories->firstItem() + $loop->index }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->slug }}</td>
                                        <td>
                                            @if ($category->icon)
                                                <img src="{{ asset($category->icon) }}" alt="{{ $category->name }}" width="45" height="45" class="rounded border" style="object-fit: cover;">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($category->status)
                                                <label class="badge badge-success">Active</label>
                                            @else
                                                <label class="badge badge-danger">Inactive</label>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline delete-category-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No categories found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $categories->links() }}
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
            var forms = document.querySelectorAll('.delete-category-form');

            forms.forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    swal({
                        title: 'Delete category?',
                        text: 'This category will be removed.',
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
