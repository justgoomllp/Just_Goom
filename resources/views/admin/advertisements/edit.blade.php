@extends('admin.layouts.app')

@section('title', 'Edit Advertisement')
@section('page-title', 'Edit Advertisement')

@section('content')
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Advertisement</h4>

                    <form method="POST" action="{{ route('admin.advertisements.update', $advertisement) }}" enctype="multipart/form-data" class="js-admin-validate js-dirty-update" data-confirm-title="Save these changes?" data-confirm-text="The advertisement will be updated with your new details." @if ($errors->any()) data-dirty-start="1" @endif novalidate>
                        @csrf
                        @method('PUT')

                        @include('admin.advertisements._form', [
                            'ad' => $advertisement,
                            'users' => $users,
                            'buttonText' => 'Update',
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
