<!DOCTYPE html>
<html lang="{{ \Illuminate\Support\Str::replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Admin'))</title>

    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-custom.css') }}">
    @include('partials.favicon', ['favicon' => 'assets/images/favicon.png'])
    @stack('styles')
</head>
<body>
    <div class="container-scroller d-flex">
        @include('admin.partials.sidebar')

        <div class="container-fluid page-body-wrapper">
            @include('admin.partials.header')

            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>

                @include('admin.partials.footer')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    @stack('vendor-scripts')
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    @stack('scripts')
</body>
</html>
