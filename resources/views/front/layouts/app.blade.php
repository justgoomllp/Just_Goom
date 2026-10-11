<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Just Goom LLP')</title>
  @include('partials.favicon')
  @hasSection('meta_description')
    <meta name="description" content="@yield('meta_description')">
  @endif
  <link rel="stylesheet" href="{{ asset('front/assets/css/style.css') }}?v=8">
  <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
  @stack('styles')
</head>
<body @yield('body_attrs')>

  @include('front.partials.header')

  @yield('content')

  @include($footerPartial ?? 'front.partials.footer')

  @stack('scripts')
  @php
    $jgFlash = array_filter([
        'success' => session('success'),
        'error' => session('error'),
        'info' => session('info'),
        'warning' => session('warning'),
    ]);
  @endphp
  @if($jgFlash !== [])
    <script>
      window.JG_FLASH = @json($jgFlash);
    </script>
  @endif
  @if(session('celebrate'))
    <script>
      window.JG_CELEBRATE = @json(session('celebrate'));
    </script>
  @endif
  <script src="{{ asset('front/assets/js/celebrate.js') }}?v=3"></script>
  <script>if (typeof JGCelebrateBoot === 'function') JGCelebrateBoot();</script>
  <script src="{{ asset('front/assets/js/main.js') }}?v=6"></script>
</body>
</html>
