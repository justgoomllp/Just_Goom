@extends('admin.layouts.app')

@section('title', 'Add Sub Category')
@section('page-title', 'Add Sub Category')

@section('content')
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Sub Category Details</h4>

                    <form method="POST" action="{{ route('admin.sub-categories.store') }}" enctype="multipart/form-data">
                        @csrf

                        @include('admin.sub_categories._form', [
                            'subCategory' => null,
                            'buttonText' => 'Save',
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
