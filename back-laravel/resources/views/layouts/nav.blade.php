<!-- Navbar -->
<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{route('home')}}">IgestShop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('default_catalogue') }}">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('default_catalogue', ['discount' => 1]) }}">On Sale</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('default_catalogue', ['collection' => $latestCollection->id]) }}">New Collections</a>
                    </li>
                    @auth
                        @can('admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin_default_catalogue') }}">Admin Products List</a>
                            </li>
                        @endcan
                    @endauth
                </ul>
                        <form method="GET" action="{{ route('default_catalogue') }}" id="searchForm" class="d-flex align-items-center">
                            <input class="form-control search-bar me-2" type="search" id="searchInput" name="search" placeholder="Search for products..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-search">Search</button>
                        </form>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('cart')}}" aria-label="Cart"><i class="bi bi-cart"></i></a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('login')}}">Login</a>
                        </li>
                    @endguest
                    @auth()
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('profile') }}">
                                {{ Auth::user()->getFullName() ?: Auth::user()->email }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="nav-link btn-danger btn-sm" type="submit"> Logout </button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>
