@extends('admin.layouts.app')

@section('title', 'Edit Advertisement')
@section('page-title', 'Edit Advertisement')

@section('content')
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Advertisement</h4>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.advertisements.update', $advertisement) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        @include('admin.advertisements._form', ['ad' => $advertisement])
                        <button type="submit" class="btn btn-primary">Update Advertisement</button>
                        <a href="{{ route('admin.advertisements.index') }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
