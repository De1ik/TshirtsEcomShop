@extends('layouts.layout')

@section('title', 'IgestShop - Admin Products')

@section('styles')
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="{{ asset('css/admin_products_list.css') }}" rel="stylesheet">
  <link href="{{ asset('css/layout.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
    <!-- Main Content -->
    <main class="container my-5">
        <h2 class="mb-4">Product List</h2>
        <div class="row">
            <!-- Filter Sidebar -->
       <aside class="col-lg-3 col-md-4 mb-4">
         <form method="GET" action="{{ route('admin_default_catalogue') }}">
           @if(request('discount') == 1)
              <input type="hidden" name="discount" value="1">
           @endif
           <div class="filter-sidebar">
             <h5>FILTERS</h5>
             <hr>

             {{-- Gender --}}
             <div class="mb-4">
               <h6>Gender</h6>
               @foreach ($genders as $gender)
                 <div class="form-check">
                   <input
                     class="form-check-input"
                     type="radio"
                     name="gender"
                     id="gender-{{ $gender }}"
                     value="{{ $gender }}"
                     {{ request('gender') == $gender ? 'checked' : '' }}
                   >
                   <label class="form-check-label" for="gender-{{ $gender }}">{{ ucfirst($gender) }}</label>
                 </div>
               @endforeach
             </div>

             {{-- Category --}}
             <div class="mb-4">
               <h6>Category</h6>
               <select class="form-select" name="category">
                 <option value="">All Categories</option>
                 @foreach ($categories as $category)
                   <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                     {{ ucfirst($category) }}
                   </option>
                 @endforeach
               </select>
             </div>

             {{-- Collection --}}
             <div class="mb-4">
               <h6>STYLE</h6>
               <select class="form-select" name="collection">
                 <option value="">All Collections</option>
                 @foreach ($collections as $id => $name)
                     <option value="{{ $id }}" {{ request('collection') == $id ? 'selected' : '' }}>{{ $name }}</option>
                 @endforeach
               </select>
             </div>

             {{-- Price --}}
             <div class="mb-4">
               <h6>PRICE</h6>
               <div>
                 <label for="minPrice" class="form-label">Min Price: <span id="minPriceValue">€{{ request('min_price', $minPrice) }}</span></label>
                 <input type="range" class="form-range" id="minPrice" name="min-price" min="{{ $minPrice }}" max="{{ $maxPrice }}" value="{{ request('min-price', $minPrice) }}">
               </div>
               <div>
                 <label for="maxPrice" class="form-label">Max Price: <span id="maxPriceValue">€{{ request('max_price', $maxPrice) }}</span></label>
                 <input type="range" class="form-range" id="maxPrice" name="max-price" min="{{ $minPrice }}" max="{{ $maxPrice }}" value="{{ request('max-price', $maxPrice) }}">
               </div>
               <div class="price-range-labels">
                 <p>Range from €{{ $minPrice }} to €{{ $maxPrice }}</p>
               </div>
             </div>

             {{-- Size — пока без name --}}
             <div class="mb-4">
               <h6>SIZE</h6>
               <div class="d-flex flex-wrap">
                 <div class="form-group mb-3">
                   <div class="size-selector">
                     @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                       <div class="form-check form-check-inline">
                         <input
                           class="form-check-input"
                           type="checkbox"
                           name="sizes[]"
                           id="size-{{ $size }}"
                           value="{{ $size }}"
                           {{ in_array($size, request()->get('sizes', [])) ? 'checked' : '' }}
                         >
                         <label class="form-check-label size-label" for="size-{{ $size }}">{{ $size }}</label>
                       </div>
                     @endforeach
                   </div>
                 </div>
               </div>
             </div>




             {{-- Submit --}}
             <button type="submit" class="btn btn-dark">APPLY FILTER</button>
           </div>
         </form>
       </aside>
            <!-- Product List -->
            <section class="col-lg-9 col-md-8">
                <!-- Search and Sort Bar -->
                <div class="search-sort-bar">
                    <div class="d-flex align-items-center">
                        <form method="GET" action="{{ route('admin_default_catalogue') }}" id="searchForm" class="d-flex align-items-center">
                            <div class="form-check me-3">
                                <input type="hidden" name="search_by_id" value="0">
                                <input class="form-check-input" type="checkbox" id="search-by-id" name="search_by_id" value="1" {{ request('search_by_id') }}>
                                <label class="form-check-label" for="search-by-id">Search by ID</label>

                            </div>

                            <input class="form-control search-bar me-2" type="search" id="searchInput" name="search" placeholder="Search for products..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-search">Search</button>

                            {{-- Сохраняем текущие фильтры, чтобы не сбрасывались при поиске --}}
                            <input type="hidden" name="category" value="{{ request('category') }}">
                            <input type="hidden" name="gender" value="{{ request('gender') }}">
                            <input type="hidden" name="collection" value="{{ request('collection') }}">
                            <input type="hidden" name="minPrice" value="{{ request('minPrice') }}">
                            <input type="hidden" name="maxPrice" value="{{ request('maxPrice') }}">
                            @foreach (request('sizes', []) as $size)
                                <input type="hidden" name="sizes[]" value="{{ $size }}">
                            @endforeach
                            @if(request('discount') == 1)
                                <input type="hidden" name="discount" value="1">
                            @endif
                        </form>
                    </div>
                    <span id="showingText"> Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results </span>
                    <form method="GET" action="{{ route('admin_default_catalogue') }}" id="sortForm">
                        <select name="sort" class="form-select d-inline-block w-auto ms-2" onchange="document.getElementById('sortForm').submit()">
                            <option value="">Sort by</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="release_desc" {{ request('sort') === 'release_desc' ? 'selected' : '' }}>Release: Newest to Oldest</option>
                            <option value="release_asc" {{ request('sort') === 'release_asc' ? 'selected' : '' }}>Release: Oldest to Newest</option>
                        </select>

                        {{-- Сохраняем текущие фильтры, чтобы не сбрасывались при сортировке --}}
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <input type="hidden" name="gender" value="{{ request('gender') }}">
                        <input type="hidden" name="collection" value="{{ request('collection') }}">
                        <input type="hidden" name="minPrice" value="{{ request('minPrice') }}">
                        <input type="hidden" name="maxPrice" value="{{ request('maxPrice') }}">
                        @foreach (request('sizes', []) as $size)
                            <input type="hidden" name="sizes[]" value="{{ $size }}">
                        @endforeach
                        @if(request('discount') == 1)
                            <input type="hidden" name="discount" value="1">
                        @endif
                    </form>
                </div>



                <!-- Product Cards -->
                <section id="product-list">
                @foreach ($products as $product)
                <article class="product-card" data-price="60">
                    <img src="{{ asset(optional($product->mainImage)->image_url ? 'storage/' . $product->mainImage->image_url : 'images/default.png') }}" alt="{{ $product->name }}">
                    <div class="flex-grow-1">
                        <h6>{{ $product->name }}</h6>
                        <div class="product-details">
                            <p>ID: {{ $product->id }}</p>
                            @php
                              $original = $product->price;
                              $discounted = $product->activeDiscount?->new_price;
                              $discountPercent = $discounted ? round(100 - ($discounted / $original * 100)) : null;
                            @endphp
                            @if ($discounted)
                              <div class="discount-badge">-{{ $discountPercent }}%</div>
                            @endif
                            <p>
                                @if ($product->is_discount)
                                    €{{ $discounted }}
                                @else
                                    €{{ $product->price }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="./admin_update_product.html" class="product-link"><i class="bi bi-pencil"></i></a>
                </article>
                @endforeach
                </section>

                <!-- Compact Pagination Section -->
                <div class="pagination-wrapper">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </section>
        </div>
    </main>

    <!-- Banner -->
    <section class="banner">
        ENJOY EVERY MOMENT!
    </section>

@endsection
@section('scripts')

    <!-- Bootstrap JS and Icons -->
    <!-- JavaScript for Size Button Toggle and Price Range Filter -->
    <script>
        // Size Button Toggle
        document.querySelectorAll('.size-btn').forEach(button => {
            button.addEventListener('click', () => {
                button.classList.toggle('active');
            });
        });

        // Price Range Filter
        const minPriceInput = document.getElementById('minPrice');
        const maxPriceInput = document.getElementById('maxPrice');
        const minPriceValue = document.getElementById('minPriceValue');
        const maxPriceValue = document.getElementById('maxPriceValue');
        const productCards = document.querySelectorAll('.product-card');
        const showingText = document.getElementById('showingText');

        // Update displayed values and ensure min <= max
        function updatePriceRange() {
            let minPrice = parseInt(minPriceInput.value);
            let maxPrice = parseInt(maxPriceInput.value);

            // Ensure minPrice is not greater than maxPrice
            if (minPrice > maxPrice) {
                minPriceInput.value = maxPrice;
                minPrice = maxPrice;
            }
            // Ensure maxPrice is not less than minPrice
            if (maxPrice < minPrice) {
                maxPriceInput.value = minPrice;
                maxPrice = minPrice;
            }

            // Update displayed values
            minPriceValue.textContent = `€${minPrice}`;
            maxPriceValue.textContent = `€${maxPrice}`;
        }

        // Apply filters based on price range
        function applyFilters() {
            const minPrice = parseInt(minPriceInput.value);
            const maxPrice = parseInt(maxPriceInput.value);
            let visibleProducts = 0;

            productCards.forEach(card => {
                const price = parseInt(card.getAttribute('data-price'));
                if (price >= minPrice && price <= maxPrice) {
                    card.style.display = 'flex';
                    visibleProducts, visibleProducts++;
                } else {
                    card.style.display = 'none';
                }
            });

        }

        // Initialize price range values
        updatePriceRange();

        // Add event listeners for price range inputs
        minPriceInput.addEventListener('input', () => {
            updatePriceRange();
            // applyFilters();
        });

        maxPriceInput.addEventListener('input', () => {
            updatePriceRange();
            // applyFilters();
        });
</script>
@endsection
