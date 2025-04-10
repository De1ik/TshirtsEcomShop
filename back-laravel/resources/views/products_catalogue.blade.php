@extends('layouts.layout')

// @section('title', 'IgestShop - T-Shirts')

@section('styles')
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="{{ asset('css/page_list.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="{{ asset('css/layout.css') }}" rel="stylesheet">
@endsection

@section('content')
  <main class="container my-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Products</li>
      </ol>
    </nav>

    <div class="row">
      <aside class="col-lg-3 col-md-4 mb-4">
        <div class="filter-sidebar">
          <h5>FILTERS</h5>
          <hr>
          <div class="mb-4">
            <h6>SEX</h6>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="sex" id="men">
              <label class="form-check-label" for="men">Men</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="sex" id="women">
              <label class="form-check-label" for="women">Women</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="sex" id="unisex">
              <label class="form-check-label" for="unisex">Unisex</label>
            </div>
          </div>
          <div class="mb-4">
            <h6>CATEGORY</h6>
            <select class="form-select">
              <option>Hoodie</option>
            </select>
          </div>
          <div class="mb-4">
            <h6>STYLE</h6>
            <select class="form-select">
              <option>Line name 1</option>
              <option>Line name 2</option>
              <option>Line name 3</option>
              <option>Line name 4</option>
            </select>
          </div>
          <div class="mb-4">
            <h6>PRICE</h6>
            <div>
              <label for="minPrice" class="form-label">Min Price: <span id="minPriceValue">€0</span></label>
              <input type="range" class="form-range" id="minPrice" min="0" max="500" value="0">
            </div>
            <div>
              <label for="maxPrice" class="form-label">Max Price: <span id="maxPriceValue">€100</span></label>
              <input type="range" class="form-range" id="maxPrice" min="0" max="500" value="300">
            </div>
            <div class="price-range-labels">
              <p>Range from €0 to €500</p>
            </div>
          </div>
          <div class="mb-4">
            <h6>SIZE</h6>
            <div class="d-flex flex-wrap">
              <button class="size-btn">XXS</button>
              <button class="size-btn">XS</button>
              <button class="size-btn active">S</button>
              <button class="size-btn">M</button>
              <button class="size-btn">L</button>
              <button class="size-btn">XL</button>
              <button class="size-btn">XXL</button>
              <button class="size-btn">3XL</button>
              <button class="size-btn">4XL</button>
            </div>
          </div>
          <button class="btn">APPLY FILTER</button>
        </div>
      </aside>

      <section class="col-lg-9 col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3>Our T-Shirts</h3>
          <div class="sort-select">
              <span id="showingText"> Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results </span>
            <select class="form-select d-inline-block w-auto ms-2">
              <option>Sort by: Lower Price</option>
            </select>
          </div>
        </div>

        <div class="row">
          @foreach ($products as $product)
            <article class="col-lg-4 col-md-6 mb-4">
              <a href="" class="product-card-link">
                <div class="product-card position-relative" data-price="{{ $product->price }}">
                  @if ($product->is_discount)
                    <div class="discount-badge">-{{ $product->discount_percent }}%</div>
                  @endif
                  <img src="{{ asset(optional($product->mainImage)->image_url ? 'images/products/' . $product->mainImage->image_url : 'images/default.png') }}" alt="{{ $product->main_image_url }}">
                  <h6>{{ $product->name }}</h6>
                  <div class="star-rating d-flex justify-content-center" data-rating="{{ round($product->reviews_avg_rating ?? 0, 1) }}" data-amount="{{ $product->reviews_count }}">
                    <!-- Stars will be dynamically generated -->
                  </div>
                  <p class="price">
                    €{{ $product->price }}
                    @if ($product->is_discount)
                      <span class="discount">€{{ $product->original_price }}</span>
                    @endif
                  </p>
                </div>
              </a>
            </article>
          @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
      </section>
    </div>
  </main>

  <section class="banner">
    <div>
      <p>ENJOY EVERY MOMENT!</p>
    </div>
  </section>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Size Button Toggle
      document.querySelectorAll('.size-btn').forEach(button => {
        button.addEventListener('click', () => {
          button.classList.toggle('active');
        });
      });

      // Star Rating Generator
      document.querySelectorAll('.star-rating').forEach(ratingElement => {
        const rating = parseFloat(ratingElement.getAttribute('data-rating'));
        const amount = parseFloat(ratingElement.getAttribute('data-amount'));
        let starsHTML = '';

        for (let i = 1; i <= 5; i++) {
          if (rating >= i) {
            starsHTML += '<i class="bi bi-star-fill"></i>';
          } else if (rating >= i - 0.5) {
            starsHTML += '<i class="bi bi-star-half"></i>';
          } else {
            starsHTML += '<i class="bi bi-star"></i>';
          }
        }

        starsHTML += `<span class="ms-2">${amount}</span>`;
        ratingElement.innerHTML = starsHTML;
      });

      // Price Range Filter
      const minPriceInput = document.getElementById('minPrice');
      const maxPriceInput = document.getElementById('maxPrice');
      const minPriceValue = document.getElementById('minPriceValue');
      const maxPriceValue = document.getElementById('maxPriceValue');
      const productCards = document.querySelectorAll('.product-card');
//       const showingText = document.getElementById('showingText');

      function updatePriceRange() {
        let minPrice = parseInt(minPriceInput.value);
        let maxPrice = parseInt(maxPriceInput.value);

        if (minPrice > maxPrice) {
          minPriceInput.value = maxPrice;
          minPrice = maxPrice;
        }
        if (maxPrice < minPrice) {
          maxPriceInput.value = minPrice;
          maxPrice = minPrice;
        }

        minPriceValue.textContent = `€${minPrice}`;
        maxPriceValue.textContent = `€${maxPrice}`;

        applyFilters();
      }

      function applyFilters() {
        const minPrice = parseInt(minPriceInput.value);
        const maxPrice = parseInt(maxPriceInput.value);
        let visibleProducts = 0;

        productCards.forEach(card => {
          const price = parseInt(card.getAttribute('data-price'));
          if (price >= minPrice && price <= maxPrice) {
            card.style.display = 'flex';
            visibleProducts++;
          } else {
            card.style.display = 'none';
          }
        });

//         if (showingText) {
//           showingText.textContent = `Showing 1-${visibleProducts} products`;
//         }
      }

      updatePriceRange();

      minPriceInput.addEventListener('input', updatePriceRange);
      maxPriceInput.addEventListener('input', updatePriceRange);
    });
  </script>
@endsection
