<header >

<div class="nav-header">
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <a class="navbar-brand main-logo" href="{{ route('home') }}">
                    <img src="{{ asset('/front/images/logo.svg') }}" alt="" :title="APP_NAME">
                    </a>
                </div>
                <div class="col-6">
                    <div class="pull-right">
                        <div id="wrap" class="header-search-panel">
                            <form action="{{ route('products') }}" autocomplete="on" method="get">
                                <input id="search" name="search" type="text" placeholder="Search" autocomplete="off">
                                <input id="search_submit" value="Rechercher" type="submit">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
        
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('static.pages','how-it-works') }}">How it works</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('static.pages','aboutus') }}">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('blog') }}">Blogs</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>
</div>
</header>