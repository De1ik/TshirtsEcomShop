@php use App\Models\Product; @endphp
@extends('layouts.layout')

@section('title', 'Your Cart')

@section('styles')
    <link href="{{ asset('css/cart.css') }}" rel="stylesheet">
@endsection

@section('content')
    <main class="container my-5">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cart</li>
            </ol>
        </nav>

        <h2 class="mb-4">Your Cart</h2>
        <div class="row">
            <!-- Cart Items Section -->
            <section class="col-lg-8 col-md-7 mb-4">
                @if($cart && $cart->items && $cart->items->count())
                    @foreach($cart->items ?? $cart as $item)
                        @php
                            $isArray = is_array($item);
                            $product = $isArray ? Product::find($item['product_id']) : $item->variant->product;
                            $image = $isArray ? $item['image_url'] : $product->mainImage->image_url ?? 'images/tshirt-noback/tshirt-logo-1.png';
                            $productId = $isArray ? $item['product_id'] : $product->id;
                            $productName = $isArray ? $item['product_name'] : $product->name;
                            $colorHex = $isArray ? $item['color_hex'] : ($item->variant->color->hex_code ?? '#000');
                            $size = $isArray ? $item['size'] : $item->variant->size;
                            $quantity = $isArray ? $item['quantity'] : $item->quantity;
                            $unitPrice = $isArray ? $item['unit_price'] : $item->unit_price;
                        @endphp

                        <article class="cart-item d-flex mb-3">
                            <div class="item-details d-flex">
                                <a href="{{route('product.details', $productId)}}">
                                    <img src="{{ asset($image ? 'storage/product-photos/' . $image : 'images/default.png') }}" alt="{{ $productName }}">
                                </a>
                                <div class="flex-grow-1 ms-3">
                                    <h6>{{ $productName }}</h6>
                                    <p class="product-id">ID: <span class="product-id-value">{{ $productId }}</span></p>
                                    <p>Size: {{ $size }}</p>
                                    <p>Color:
                                        <span
                                            class="cart-image-color-span" style="background-color: {{ $colorHex }};"></span>
                                    </p>
                                    <p>€{{ number_format($unitPrice, 2) }}</p>
                                </div>
                            </div>
                            <div class="item-actions mt-2">
                                <div class="quantity-selector d-flex">
                                    <form action="{{ route('cart.decrease', $item->id) }}" method="POST" class="me-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="quantity-btn">-</button>
                                    </form>
                                    <input type="text" value="{{ $quantity }}" readonly>
                                    <form action="{{ route('cart.increase', $item->id) }}" method="POST" class="ms-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="quantity-btn">+</button>
                                    </form>
                                </div>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="remove-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="remove-btn btn btn-link">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                @else
                    <p>Your cart is empty.</p>
                @endif
            </section>

            @php
                $subtotal = 0;
                $totalDiscount = 0;

                if ($cart && $cart->items) {
                    foreach ($cart->items as $item) {
                        $product = $item->variant->product;
                        $discount = $product->activeDiscount;

                        $originalPrice = $item->unit_price;
                        $finalPrice = $discount ? $discount->new_price : $originalPrice;

                        $subtotal += $originalPrice * $item->quantity;

                        if ($discount) {
                            $totalDiscount += ($originalPrice - $finalPrice) * $item->quantity;
                        }
                    }
                }

                $total = $subtotal - $totalDiscount;
            @endphp

            @if($cart && $cart->items && $cart->items->count())
            <aside class="col-lg-4 col-md-5">
                <div class="order-summary p-3 border">
                    <h5>Order Summary</h5>

                    <div class="d-flex justify-content-between mb-2">
                        <p>Subtotal</p>
                        <p>€{{ number_format($subtotal, 2) }}</p>
                    </div>

                    @if ($totalDiscount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <p>Discount</p>
                            <p>-€{{ number_format($totalDiscount, 2) }}</p>
                        </div>
                    @endif
                    <hr>

                    <div class="d-flex justify-content-between mb-3">
                        <p class="total">Total</p>
                        <p class="total">€{{ number_format($total, 2) }}</p>
                    </div>

                    <form action="{{ route('checkout') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Choose Delivery Method:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery_method" id="courier" value="courier" checked>
                                <label class="form-check-label" for="courier">Courier</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery_method" id="packeta" value="packeta">
                                <label class="form-check-label" for="packeta">Packeta</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery_method" id="mail" value="mail">
                                <label class="form-check-label" for="mail">Mail</label>
                            </div>
                        </div>

                        <button type="submit" class="go-to-shipping btn btn-primary d-block w-100">Go to Shipping</button>
                    </form>

                </div>
            </aside>
            @endif
        </div>
    </main>

    <!-- Banner Section -->
    <section class="banner text-center py-4">
        <p>ENJOY EVERY MOMENT!</p>
    </section>
@endsection
