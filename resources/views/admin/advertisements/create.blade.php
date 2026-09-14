@extends('admin.layouts.app')

@section('title', 'Add Advertisement')
@section('page-title', 'Add Advertisement')

@section('content')
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Advertisement Details</h4>

                    <form method="POST" action="{{ route('admin.advertisements.store') }}" enctype="multipart/form-data" class="js-admin-validate" novalidate>
                        @csrf

                        @include('admin.advertisements._form', [
                            'ad' => null,
                            'users' => $users,
                            'buttonText' => 'Save',
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
