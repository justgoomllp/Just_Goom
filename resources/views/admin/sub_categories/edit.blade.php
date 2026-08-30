@extends('admin.layouts.app')

@section('title', 'Edit Sub Category')
@section('page-title', 'Edit Sub Category')

@section('content')
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Sub Category</h4>

                    <form method="POST" action="{{ route('admin.sub-categories.update', $subCategory) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('admin.sub_categories._form', [
                            'subCategory' => $subCategory,
                            'buttonText' => 'Update',
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
