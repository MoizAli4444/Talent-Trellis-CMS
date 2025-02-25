<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="/dashboard">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Interface</div>
                
                <a class="nav-link" href="{{ route('posts.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-pencil-alt"></i></div>
                    Posts
                </a>
                
                <a class="nav-link" href="{{ route('articles.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                    Articles
                </a>
                
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ auth()->user()?->name ?? 'Guest' }}
        </div>
    </nav>
</div>
