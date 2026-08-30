<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard — Just Goom')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @include('partials.favicon')
  <link rel="stylesheet" href="{{ asset('front/assets/css/users.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
  @stack('styles')
</head>
<body class="user-panel-body" @yield('body_attrs')>

  <div class="user-sidebar-overlay"></div>
  <div class="user-wrap">
    <aside class="user-sidebar" id="userSidebar">
      @include('front.partials.users.sidebar')
    </aside>
    <div class="user-main">
      @include('front.partials.users.header')
      @yield('content')
      @include('front.partials.users.footer')
    </div>
  </div>

  @if(empty($hasActivePlan))
    @include('front.partials.users.plan-required-modal')
  @endif

  @stack('scripts')
  @if(session('success') || session('error') || session('info'))
    <script>
      window.JG_FLASH = @json(['success' => session('success'), 'error' => session('error'), 'info' => session('info')]);
    </script>
  @endif
  <script>
    window.JG_USER = @json(['name' => auth()->user()?->companyProfile?->company_name ?? auth()->user()?->fullName() ?? 'User', 'email' => auth()->user()?->email]);
  </script>
  <script src="{{ asset('front/assets/js/users-nav.js') }}"></script>
  <script src="{{ asset('front/assets/js/users.js') }}"></script>
</body>
</html>
