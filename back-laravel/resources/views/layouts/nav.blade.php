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
                        <a class="nav-link" href="./product/page_list.html">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./product/page_list.html">On Sale</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./product/page_list.html">New Collections</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin/admin_products_list.html">Admin Products List</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control search-bar me-2" type="search" placeholder="Search products..." aria-label="Search">
                </form>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./order/cart.html" aria-label="Cart"><i class="bi bi-cart"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('login')}}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
