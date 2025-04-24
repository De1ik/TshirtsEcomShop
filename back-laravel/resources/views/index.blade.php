@extends('layouts.layout')

@section('styles')
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
@endsection

@section('content')
    <main>
        <!-- Hero Section -->
        <section class="container my-5">
            <div class="hero-section">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <p>Discover timeless style and quality you can trust. Explore our curated collection of premium essentials, made to elevate your everyday.</p>
                        <div class="stats">
                            <div>
                                <h3>Unique</h3>
                                <p>Luxury Collections</p>
                            </div>
                            <div>
                                <h3>Only</h3>
                                <p>High-Quality Products</p>
                            </div>
                            <div>
                                <h3>Every</h3>
                                <p>Customer is Happy</p>
                            </div>
                        </div>
                        <a href="{{ route('default_catalogue') }}" class="btn btn-shop-now">Shop Now</a>
                    </div>
                    <div class="col-lg-6 text-end">
                        <img src="{{ asset('images/brand-tshirt.png' ) }}" alt="Brand T-Shirt" class="main-image">
                    </div>
                </div>
            </div>
        </section>

        <!-- Choose Yourself Section -->
        <section class="container choose-yourself-section">
            <h2>Choose Yourself</h2>
            <div class="horizontal-carousel">
                <div class="carousel-track" id="carouselTrack">
                    @foreach($products as $product)
                    <a href="{{route('product.details', $product->id)}}">
                        <img src="{{ asset(optional($product->mainImage)->image_url ? 'storage/product-photos/' . $product->mainImage->image_url : 'images/default.png') }}" alt="{{ $product->name }}">
                    </a>
                    @endforeach
                </div>
                <button class="carousel-btn prev" id="prevBtn" aria-label="Previous">←</button>
                <button class="carousel-btn next" id="nextBtn" aria-label="Next">→</button>
            </div>
        </section>

        <!-- New Collection Section -->
        <section class="container new-collection-section">
            <h2>New Collection</h2>
            <div class="row">
                @foreach($last_collection_products as $product)
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="{{route('product.details', $product->id)}}" class="product-card-link">
                        <article class="product-card position-relative">
                            @php
                              $original = $product->price;
                              $discounted = $product->activeDiscount?->new_price;
                              $discountPercent = $discounted ? round(100 - ($discounted / $original * 100)) : null;
                            @endphp

                            @if ($discounted)
                              <div class="discount-badge">-{{ $discountPercent }}%</div>
                            @endif
                            <img src="{{ asset(optional($product->mainImage)->image_url ? 'storage/product-photos/' . $product->mainImage->image_url : 'images/default.png') }}" alt="{{ $product->name }}">
                            <h6>{{ $product->name }}</h6>
                            <div class="star-rating d-flex justify-content-center" data-rating="{{ round($product->reviews_avg_rating ?? 0, 1) }}" data-amount="{{ $product->reviews_count }}">
                                <!-- Stars will be dynamically generated -->
                            </div>
                                <p class="price">
                                    @if ($product->is_discount)
                                        €{{ $discounted }}
                                        <span class="discount">€{{ $product->price }}</span>
                                    @else
                                        €{{ $product->price }}
                                    @endif
                              </p>
                        </article>
                    </a>
                </div>
                @endforeach
            </div>
            <a href="{{ route('default_catalogue', ['collection' => $collection_id]) }}" class="view-all-btn mt-4">View All</a>
        </section>

        <!-- Banner -->
        <div class="banner" role="banner">
            ENJOY EVERY MOMENT!
        </div>
    </main>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carouselTrack = document.getElementById('carouselTrack');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const products = carouselTrack.querySelectorAll('a');
        const productWidth = products[0].offsetWidth; // Assuming all images have the same width
        const visibleCount = Math.round(carouselTrack.offsetWidth / productWidth);
        let currentIndex = 0;

        // Clone the first and last few elements to create the infinite effect
        const cloneFirst = Array.from(products).slice(0, visibleCount).map(node => node.cloneNode(true));
        const cloneLast = Array.from(products).slice(-visibleCount).map(node => node.cloneNode(true));

        cloneFirst.forEach(clone => carouselTrack.appendChild(clone));
        cloneLast.forEach(clone => carouselTrack.prepend(clone));

        // Adjust the initial position to the cloned last elements
        carouselTrack.style.transform = `translateX(-${visibleCount * productWidth}px)`;

        function scrollTo(index, smooth = true) {
            carouselTrack.style.scrollBehavior = smooth ? 'smooth' : 'auto';
            carouselTrack.style.transform = `translateX(-${(visibleCount + index) * productWidth}px)`;
            currentIndex = index;
        }

        nextBtn.addEventListener('click', () => {
            scrollTo(currentIndex + 1);
        });

        prevBtn.addEventListener('click', () => {
            scrollTo(currentIndex - 1);
        });

        carouselTrack.addEventListener('transitionend', () => {
            if (currentIndex >= products.length) {
                carouselTrack.style.scrollBehavior = 'auto';
                carouselTrack.style.transform = `translateX(-${visibleCount * productWidth}px)`;
                currentIndex = 0;
            } else if (currentIndex < 0) {
                carouselTrack.style.scrollBehavior = 'auto';
                carouselTrack.style.transform = `translateX(-${(products.length + visibleCount) * productWidth}px)`;
                currentIndex = products.length - 1;
            }
    });

    // Make the carousel responsive
    function updateCarousel() {
        const newVisibleCount = Math.round(carouselTrack.offsetWidth / productWidth);
        const newCloneFirst = Array.from(products).slice(0, newVisibleCount).map(node => node.cloneNode(true));
        const newCloneLast = Array.from(products).slice(-newVisibleCount).map(node => node.cloneNode(true));

        // Remove old clones
        const existingClones = carouselTrack.querySelectorAll('.carousel-track > a.clone');
        existingClones.forEach(clone => carouselTrack.removeChild(clone));

        // Append new clones
        newCloneFirst.forEach(clone => {
            const clonedElement = clone.cloneNode(true);
            clonedElement.classList.add('clone');
            carouselTrack.appendChild(clonedElement);
        });
        newCloneLast.forEach(clone => {
            const clonedElement = clone.cloneNode(true);
            clonedElement.classList.add('clone');
            carouselTrack.prepend(clonedElement);
        });

        // Recalculate visibleCount and adjust position
        visibleCount = newVisibleCount;
        carouselTrack.style.scrollBehavior = 'auto';
        carouselTrack.style.transform = `translateX(-${(visibleCount + currentIndex) * productWidth}px)`;
    }

    window.addEventListener('resize', updateCarousel);
    updateCarousel(); // Initial call to set up based on initial width
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
</script>
@endsection

