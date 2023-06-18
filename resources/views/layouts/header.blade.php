<header>
    <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand main-logo" href="{{ route('home') }}">
        <img src="{{ asset('/front/images/logo.svg') }}" alt="" :title="APP_NAME">
        </a>
		<div id="wrap" class="header-search-panel header-mobile-search">
            <form action="{{ route('products') }}" autocomplete="on" method="get">
                <input id="search" name="search" type="text" placeholder="Search" autocomplete="off">
                <input id="search_submit" value="Rechercher" type="submit">
            </form>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('static.pages','how-it-works') }}">How it works</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('static.pages','aboutus') }}">About Us</a>
            </li>
            <?php /*
            <li class="nav-item">
                <a class="nav-link" href="{{ route('blog') }}">Blogs</a>
            </li>
            */ ?>
        </ul>
        <div id="wrap" class="header-search-panel">
            <form action="{{ route('products') }}" autocomplete="on" method="get">
                <input id="search" name="search" type="text" placeholder="Search" autocomplete="off">
                <input id="search_submit" value="Rechercher" type="submit">
            </form>
        </div>
        </div>
    </div>
    </nav>
</header>