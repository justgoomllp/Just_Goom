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
                                            @include('admin.partials.catalog-icon', ['icon' => $subCategory->icon, 'alt' => $subCategory->name])
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
