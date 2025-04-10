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
                        <img src="{{ asset('images/products/brand-tshirt.png' ) }}" alt="Brand T-Shirt" class="main-image">
                    </div>
                </div>
            </div>
        </section>

        <!-- Choose Yourself Section -->
        <section class="container choose-yourself-section">
            <h2>Choose Yourself</h2>
            <div class="horizontal-carousel">
                <div class="carousel-track" id="carouselTrack">
                    <a href="#">
                        <img src="{{ asset('images/products/tshirt-logo-1.png' ) }}" alt="Hoodie 1">
                    </a>
                    <a href="#">
                        <img src="{{ asset('images/products/tshirt-logo-2.png' ) }}" alt="T-Shirt 1">
                    </a>
                    <a href="#">
                        <img src="{{ asset('images/products/1.png' ) }}" alt="Hoodie 2">
                    </a>
                    <a href="#">
                        <img src="{{ asset('images/products/tshirt-logo-1.png' ) }}" alt="T-Shirt 2">
                    </a>
                </div>
                <button class="carousel-btn prev" id="prevBtn" aria-label="Previous">←</button>
                <button class="carousel-btn next" id="nextBtn" aria-label="Next">→</button>
            </div>
        </section>

        <!-- New Collection Section -->
        <section class="container new-collection-section">
            <h2>New Collection</h2>
            <div class="row">
                <!-- Product 1 -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="#" class="product-card-link">
                        <article class="product-card position-relative">
                            <img src="{{ asset('images/products/tshirt-logo-1.png' ) }}" alt="Gradient Graphic T-Shirt">
                            <h6>Gradient Graphic T-Shirt</h6>
                            <div class="star-rating d-flex justify-content-center" data-rating="3.5">
                                <!-- Stars will be dynamically generated -->
                            </div>
                            <p class="price">€145</p>
                        </article>
                    </a>
                </div>
                <!-- Product 2 -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="#" class="product-card-link">
                        <article class="product-card position-relative">
                            <img src="{{ asset('images/products/tshirt-logo-1.png' ) }}" alt="Polo with Tipping Details">
                            <h6>Polo with Tipping Details</h6>
                            <div class="star-rating d-flex justify-content-center" data-rating="4.5">
                                <!-- Stars will be dynamically generated -->
                            </div>
                            <p class="price">€180</p>
                        </article>
                    </a>
                </div>
                <!-- Product 3 -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="#" class="product-card-link">
                        <article class="product-card position-relative">
                            <div class="discount-badge">-30%</div>
                            <img src="{{ asset('images/products/tshirt-logo-1.png' ) }}" alt="Black Striped T-Shirt">
                            <h6>Black Striped T-Shirt</h6>
                            <div class="star-rating d-flex justify-content-center" data-rating="5.0">
                                <!-- Stars will be dynamically generated -->
                            </div>
                            <p class="price">€220 <span class="discount">€250</span></p>
                        </article>
                    </a>
                </div>
                <!-- Product 4 -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="#" class="product-card-link">
                        <article class="product-card position-relative">
                            <div class="discount-badge">-30%</div>
                            <img src="{{ asset('images/products/tshirt-logo-1.png' ) }}" alt="Black Striped T-Shirt">
                            <h6>Black Striped T-Shirt</h6>
                            <div class="star-rating d-flex justify-content-center" data-rating="5.0">
                                <!-- Stars will be dynamically generated -->
                            </div>
                            <p class="price">€220 <span class="discount">€250</span></p>
                        </article>
                    </a>
                </div>
            </div>
            <a href="#" class="view-all-btn mt-4">View All</a>
        </section>

        <!-- Banner -->
        <div class="banner" role="banner">
            ENJOY EVERY MOMENT!
        </div>
    </main>
@endsection
