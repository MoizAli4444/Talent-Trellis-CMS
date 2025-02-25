<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'Talent Trellis')</title>

    <!-- Favicons -->
    <link href="{{ asset('landing-page-theme/assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('landing-page-theme/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('landing-page-theme/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing-page-theme/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('landing-page-theme/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('landing-page-theme/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing-page-theme/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('landing-page-theme/assets/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

    @include('layouts-public.header')

    <main class="main">
        @yield('content')
    </main>

    @include('layouts-public.footer')

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('landing-page-theme/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('landing-page-theme/assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('landing-page-theme/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('landing-page-theme/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('landing-page-theme/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('landing-page-theme/assets/js/main.js') }}"></script>

</body>

</html>
