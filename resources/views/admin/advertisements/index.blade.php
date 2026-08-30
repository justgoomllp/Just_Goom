@extends('admin.layouts.app')

@section('title', 'Advertisements')
@section('page-title', 'Advertisements')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Advertisements</h4>
                        <a href="{{ route('admin.advertisements.create') }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-plus"></i>
                            Add Advertisement
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
                                    <th>Banner</th>
                                    <th>Title</th>
                                    <th>Position</th>
                                    <th>Priority</th>
                                    <th>Period</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($advertisements as $ad)
                                    <tr>
                                        <td>{{ $loop->iteration + ($advertisements->currentPage() - 1) * $advertisements->perPage() }}</td>
                                        <td><img src="{{ asset('storage/' . $ad->banner_image) }}" alt="{{ $ad->title }}" style="height:40px; border-radius:4px;"></td>
                                        <td>{{ $ad->title }}</td>
                                        <td>{{ ucfirst($ad->position) }}</td>
                                        <td>{{ $ad->priority }}</td>
                                        <td>{{ $ad->start_date->format('d M Y') }} - {{ $ad->end_date->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge {{ $ad->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $ad->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.advertisements.edit', $ad) }}" class="btn btn-sm btn-info">Edit</a>
                                            <form action="{{ route('admin.advertisements.destroy', $ad) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this ad?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No advertisements yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $advertisements->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
