@extends('admin.layouts.app')

@section('title', 'Sub Categories')
@section('page-title', 'Sub Categories')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Sub Categories</h4>
                        <a href="{{ route('admin.sub-categories.create') }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-plus"></i>
                            Add Sub Category
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
                                    <th>Category</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Icon</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subCategories as $subCategory)
                                    <tr>
                                        <td>{{ $subCategories->firstItem() + $loop->index }}</td>
                                        <td>{{ $subCategory->category->name ?? '-' }}</td>
                                        <td>{{ $subCategory->name }}</td>
                                        <td>{{ $subCategory->slug }}</td>
                                        <td>
                                            @if ($subCategory->icon)
                                                <img src="{{ asset($subCategory->icon) }}" alt="{{ $subCategory->name }}" width="45" height="45" class="rounded border" style="object-fit: cover;">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($subCategory->status)
                                                <label class="badge badge-success">Active</label>
                                            @else
                                                <label class="badge badge-danger">Inactive</label>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.sub-categories.edit', $subCategory) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                            <form action="{{ route('admin.sub-categories.destroy', $subCategory) }}" method="POST" class="d-inline delete-sub-category-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No sub categories found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $subCategories->links() }}
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
            var forms = document.querySelectorAll('.delete-sub-category-form');

            forms.forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    swal({
                        title: 'Delete sub category?',
                        text: 'This sub category will be removed.',
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
