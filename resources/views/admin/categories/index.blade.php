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
                                            @include('admin.partials.catalog-icon', ['icon' => $category->icon, 'alt' => $category->name])
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
