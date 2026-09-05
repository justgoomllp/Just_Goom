<!DOCTYPE html>
<html lang="{{ \Illuminate\Support\Str::replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Login')</title>

    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-custom.css') }}">
    @include('partials.favicon', ['favicon' => 'assets/images/favicon.png'])
</head>
<body class="admin-auth-body">
    <div class="admin-auth">
        <div class="admin-auth-visual">
            <div>
                <div class="admin-auth-brand">Just Goom</div>
                <h2>Welcome to the Just Goom admin dashboard.</h2>
                <p>Sign in to manage categories, users, advertisements, and platform settings.</p>
            </div>
            <p class="mb-0">© {{ date('Y') }} Just Goom LLP</p>
        </div>
        <div class="admin-auth-panel">
            @yield('content')
        </div>
    </div>
</body>
</html>
