@extends('admin.layouts.app')

@section('title', 'Edit Advertisement')
@section('page-title', 'Edit Advertisement')

@section('content')
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Advertisement</h4>

                    <form method="POST" action="{{ route('admin.advertisements.update', $advertisement) }}" enctype="multipart/form-data" class="js-admin-validate" novalidate>
                        @csrf
                        @method('PUT')

                        @include('admin.advertisements._form', [
                            'ad' => $advertisement,
                            'buttonText' => 'Update',
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
