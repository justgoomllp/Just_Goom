@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Category</h4>

                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('admin.categories._form', [
                            'category' => $category,
                            'buttonText' => 'Update',
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
