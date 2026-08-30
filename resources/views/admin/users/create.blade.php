@extends('admin.layouts.app')

@section('title', 'Add User')
@section('page-title', 'Add User')

@section('content')
    <div class="row">
        <div class="col-md-10 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">User Details</h4>

                    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                        @csrf

                        @include('admin.users._form', [
                            'user' => null,
                            'buttonText' => 'Save',
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
