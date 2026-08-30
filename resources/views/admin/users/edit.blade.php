@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
    <div class="row">
        <div class="col-md-10 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit User</h4>

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('admin.users._form', [
                            'user' => $user,
                            'buttonText' => 'Update',
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>

    @php
        $profileDocuments = $user->companyProfile?->profileDocuments ?? collect();
    @endphp
    @if($user->type !== 'admin')
        <div class="row">
            <div class="col-md-10 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Company Documents</h4>
                        <p class="text-muted mb-3">Approve or unapprove KYC documents. Profile shows as Verified after at least one document is approved.</p>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Document</th>
                                        <th>Value</th>
                                        <th>Front</th>
                                        <th>Back</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($profileDocuments as $document)
                                        <tr>
                                            <td>{{ $document->business_type }}</td>
                                            <td>{{ $document->document_name }}</td>
                                            <td>{{ $document->value }}</td>
                                            <td>
                                                @if($document->front_image)
                                                    <a href="{{ asset($document->front_image) }}" target="_blank" rel="noopener">View</a>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>
                                                @if($document->back_image)
                                                    <a href="{{ asset($document->back_image) }}" target="_blank" rel="noopener">View</a>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>
                                                @if($document->isApproved())
                                                    <label class="badge badge-success">Verified</label>
                                                @elseif($document->isUnapproved())
                                                    <label class="badge badge-danger">Unapproved</label>
                                                @else
                                                    <label class="badge badge-warning">Pending</label>
                                                @endif
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.users.documents.approval', [$user, $document]) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="approved" value="1">
                                                    <button type="submit" class="btn btn-success btn-sm" @disabled($document->isApproved())>Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.users.documents.approval', [$user, $document]) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="approved" value="0">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" @disabled($document->isUnapproved())>Unapprove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No company documents uploaded.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
