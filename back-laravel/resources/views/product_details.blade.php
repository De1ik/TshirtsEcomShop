@extends('layouts.layout')

@section('styles')
    <link href="{{asset('css/product_details.css')}}" rel="stylesheet">
@endsection
@section('content')
    <main class="container my-5">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->category->name ?? 'Category' }}</li>
            </ol>
        </nav>


        <div class="row">
            <!-- Product Gallery Section -->
            @php
                $knownColors = ['red', 'green', 'blue', 'white', 'black', 'yellow', 'purple', 'pink', 'gray', 'brown', 'orange'];

                function extractColor($filename, $colors) {
                    foreach ($colors as $color) {
                        if (stripos($filename, $color) !== false) {
                            return $color;
                        }
                    }
                    return 'unknown';
                }
            @endphp

            <section class="col-lg-6 col-md-6 mb-4">
                <div class="row">
                    <!-- Main Product Gallery -->
                    <div class="product-gallery">
                        <div class="product-gallery-track" id="productGalleryTrack">
                            @foreach($product->images as $img)
                                @php
                                    $filename = pathinfo($img->image_url, PATHINFO_FILENAME);
                                    $imgColor = extractColor($filename, $knownColors);
                                @endphp

                                @if(!$selectedColor || $selectedColor === $imgColor)
                                    <img src="{{ asset('storage/product-photos/' . ($img->image_url ?? 'default.png')) }}"
                                         alt="{{ $product->name }}"
                                         class="gallery-image">
                                @endif
                            @endforeach
                        </div>
                        <button type="button" class="product-gallery-btn prev" id="productPrevBtn">←</button>
                        <button type="button" class="product-gallery-btn next" id="productNextBtn">→</button>
                    </div>

                    <!-- Thumbnails -->
                    <div class="col-12 mt-3">
                        <div class="thumbnail-gallery d-flex justify-content-center gap-2">
                            @foreach($product->images as $img)
                                @php
                                    $filename = pathinfo($img->image_url, PATHINFO_FILENAME);
                                    $imgColor = extractColor($filename, $knownColors);
                                @endphp

                                @if(!$selectedColor || $selectedColor === $imgColor)
                                    <img src="{{ asset('storage/product-photos/' . ($img->image_url ?? 'default.png')) }}"
                                         alt="Thumbnail"
                                         class="thumbnail-image">
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <!-- Product Details Article -->
            <article class="col-lg-5 col-md-5 product-details">
                <h1>{{ $product->name }}</h1>
                <div class="product-id">Product ID: {{ $product->id }}</div>
                <div class="star-rating" data-rating="{{ $product->reviews_avg_rating ?? 0 }}"></div>

                @php
                    $price = $product->price;
                    $discount = $product->activeDiscount?->new_price;
                @endphp

                <div class="mb-3">
                    <span class="price">€{{ number_format($discount ?? $price, 2) }}</span>
                    @if($discount)
                        <span class="discount">€{{ number_format($price, 2) }}</span>
                        <span class="discount-badge">-{{ round((($price - $discount) / $price) * 100) }}%</span>
                    @endif
                </div>

                <p class="mb-3">{{ $product->description }}</p>

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <h6>Select Color</h6>
                        @foreach($product->variants->unique('color_id') as $variant)
                            <a href="{{ route('product.details', ['id' => $product->id, 'color' => strtolower($variant->color->name)]) }}"
                               class="color-option"
                               style="display: inline-block; width: 30px; height: 30px; background-color: {{ $variant->color->hex_code }};
                  border: {{ request('color') === strtolower($variant->color->name) ? '2px solid black' : '1px solid #ccc' }};
                  border-radius: 50%; margin-right: 8px;">
                            </a>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <h6>Choose Size</h6>
                        <div class="d-flex flex-wrap">
                            @foreach($product->variants->unique('size') as $variant)
                                <label class="size-btn">
                                    <input type="radio" name="size" value="{{ $variant->size }}" hidden> {{ $variant->size }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="quantity-selector me-3">
                            <button type="button">-</button>
                            <input type="text" value="1" readonly>
                            <button type="button">+</button>
                        </div>
                        <button class="add-to-cart btn btn-primary" type="submit">Add to Cart</button>
                    </div>
                </form>
            </article>
        </div>

        <!-- Tabs Section for Product Details, Size Metrics, and Reviews -->
        <section class="row mt-4">
            <div class="col-lg-8 col-md-7">
                <ul class="nav nav-tabs" id="productTabs">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#details">Product Details</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews">Reviews</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="details">
                        <p>{{ $product->long_description ?? 'No additional details.' }}</p>
                    </div>
                    <div class="tab-pane fade" id="reviews">
                        @auth
                            <form action="{{ route('review.store', $product->id) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="mb-2">
                                    <label for="rating">Rating</label>
                                    <select name="rating" class="form-select" required>
                                        <option value="">Select</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="description">Review</label>
                                    <textarea name="description" class="form-control" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-outline-primary">Submit Review</button>
                            </form>
                        @else
                            <p><a href="{{ route('login') }}">Log in</a> to leave a review.</p>
                        @endauth

                        @foreach($product->reviews as $review)
                            <div class="border p-2 mb-2">
                                <strong>{{ $review->user->getFullName() ?? $review->user->email }}</strong>
                                <div class="star-rating" data-rating="{{ $review->rating }}"></div>
                                <p>{{ $review->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <aside class="col-lg-4 col-md-5">
                <h3>Similar Models</h3>
                @foreach($similarProducts as $similar)
                    <a href="{{ route('product.details', $similar->id) }}" class="product-card-link">
                        <div class="product-card">
                            <img src="{{ asset(optional($similar->mainImage)->image_url ? 'storage/product-photos/' . $similar->mainImage->image_url : 'images/default.png') }}" alt="{{ $similar->name }}">
                            <h6>{{ $similar->name }}</h6>
                            <div class="star-rating" data-rating="{{ $similar->reviews_avg_rating ?? 0 }}"></div>
                            <p class="price">€{{ number_format($similar->activeDiscount?->new_price ?? $similar->price, 2) }}</p>
                        </div>
                    </a>
                @endforeach
            </aside>
        </section>
    </main>

    <!-- Banner Section -->
    <section class="banner">
        ENJOY EVERY MOMENT!
    </section>
@endsection


@section('scripts')
    <script>
        document.querySelectorAll('.size-btn').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });

        // Color Selection
        document.querySelectorAll('.color-option').forEach(option => {
            option.addEventListener('click', () => {
                document.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');
            });
        });

        // Quantity Selector
        const quantityInput = document.querySelector('.quantity-selector input');
        document.querySelector('.quantity-selector button:first-child').addEventListener('click', () => {
            let value = parseInt(quantityInput.value);
            if (value > 1) quantityInput.value = value - 1;
        });
        document.querySelector('.quantity-selector button:last-child').addEventListener('click', () => {
            let value = parseInt(quantityInput.value);
            quantityInput.value = value + 1;
        });

        // Product Gallery Slider
        const productGalleryTrack = document.getElementById('productGalleryTrack');
        const productPrevBtn = document.getElementById('productPrevBtn');
        const productNextBtn = document.getElementById('productNextBtn');
        const productImages = productGalleryTrack.getElementsByTagName('img');
        const thumbnails = document.querySelectorAll('.thumbnail-gallery img');
        let productCurrentIndex = 0;

        function updateProductGallery() {
            if (productCurrentIndex < 0) productCurrentIndex = 0;
            if (productCurrentIndex >= productImages.length) productCurrentIndex = productImages.length - 1;
            productGalleryTrack.style.transform = `translateX(-${productCurrentIndex * 100}%)`;

            // Update active thumbnail
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            if (thumbnails[productCurrentIndex]) {
                thumbnails[productCurrentIndex].classList.add('active');
            }
        }

        productPrevBtn.addEventListener('click', () => {
            productCurrentIndex--;
            updateProductGallery();
        });

        productNextBtn.addEventListener('click', () => {
            productCurrentIndex++;
            updateProductGallery();
        });

        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', () => {
                productCurrentIndex = index;
                updateProductGallery();
            });
        });

        if (thumbnails.length > 0) {
            thumbnails[0].classList.add('active');
        }

        // Star Rating Generator
        function generateStarRating(rating) {
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
            return starsHTML;
        }

        // Apply star ratings to existing elements
        document.querySelectorAll('.star-rating').forEach(ratingElement => {
            const rating = parseFloat(ratingElement.getAttribute('data-rating'));
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

            starsHTML += `<span class="ms-2">${rating}</span>`;
            ratingElement.innerHTML = starsHTML;
        });
    </script>
@endsection
