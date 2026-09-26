@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Category</h4>

                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="js-admin-validate js-dirty-update" data-confirm-title="Save these changes?" data-confirm-text="The category will be updated with your new details." @if ($errors->any()) data-dirty-start="1" @endif novalidate>
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
