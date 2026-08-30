@extends('admin.layouts.app')

@section('title', 'Add Category')
@section('page-title', 'Add Category')

@section('content')
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Category Details</h4>

                    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                        @csrf

                        @include('admin.categories._form', [
                            'category' => null,
                            'buttonText' => 'Save',
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
