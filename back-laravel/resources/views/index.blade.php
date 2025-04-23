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
    // Horizontal Carousel
    const carouselTrack = document.getElementById('carouselTrack');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    let imageWidth = window.innerWidth <= 767 ? 100 : 50; // в процентах
    let visibleImages = window.innerWidth <= 767 ? 1 : 2;

    // Clone elements
    const originalLinks = Array.from(carouselTrack.getElementsByTagName('a'));
    const firstClones = [];
    const lastClones = [];

    for (let i = 0; i < visibleImages; i++) {
        const firstClone = originalLinks[i].cloneNode(true);
        const lastClone = originalLinks[originalLinks.length - 1 - i].cloneNode(true);
        firstClones.push(firstClone);
        lastClones.unshift(lastClone);
    }

    // add clones to the DOM
    firstClones.forEach(clone => carouselTrack.appendChild(clone));
    lastClones.forEach(clone => carouselTrack.insertBefore(clone, carouselTrack.firstChild));

    // update the links of all elements
    const allLinks = carouselTrack.getElementsByTagName('a');
    let currentIndex = visibleImages;

    // update slider
    function updateCarousel(transition = true) {
        if (transition) {
            carouselTrack.style.transition = 'transform 0.5s ease-in-out';
        } else {
            carouselTrack.style.transition = 'none';
        }
        carouselTrack.style.transform = `translateX(-${currentIndex * imageWidth}%)`;
    }

    // Buttons
    nextBtn.addEventListener('click', () => {
        currentIndex++;
        updateCarousel();

        if (currentIndex === allLinks.length - visibleImages) {
            // Переход на начало после клонированного блока
            setTimeout(() => {
                currentIndex = visibleImages;
                updateCarousel(false);
            }, 500);
        }
    });

    prevBtn.addEventListener('click', () => {
        currentIndex--;
        updateCarousel();

        if (currentIndex === 0) {
            // Go to the end after clone element
            setTimeout(() => {
                currentIndex = allLinks.length - 2 * visibleImages;
                updateCarousel(false);
            }, 500);
        }
    });


    window.addEventListener('resize', () => {
        imageWidth = window.innerWidth <= 767 ? 100 : 50;
        visibleImages = window.innerWidth <= 767 ? 1 : 2;
        updateCarousel(false);
    });

    // start point
    updateCarousel(false);


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

