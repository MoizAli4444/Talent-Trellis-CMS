<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
        <a href="{{ route('landing-page') }}" class="logo d-flex align-items-center me-auto">
            <img src="{{ asset('assets/img/logo.png') }}" alt="">
            <h1 class="sitename">Talent Trellis</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ route('landing-page') }}" class="active">Home</a></li>
                <li><a href="{{ route('latest-posts') }}">Posts</a></li>
                <li><a href="{{ route('latest-articles') }}">Articles</a></li>
                
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="btn-getstarted" href="{{ route('login') }}">Get Started</a>
    </div>
</header>
