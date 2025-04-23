@extends('layouts.layout')

@section('styles')
    <link href="{{ asset('css/submitted_order.css') }}" rel="stylesheet">
@endsection

@section('content')
    <main class="container my-5">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order</li>
            </ol>
        </nav>

        <h2 class="mb-4">Order #{{ $order->id }} was created</h2>
        <div class="row">
            <!-- Order Items Section -->
            <section class="col-lg-4 col-md-4 mb-4">
                <div class="order-items-container">
                    @foreach ($order->items as $item)
                        <article class="order-item d-flex align-items-center mb-3">
                            <a href={{route('product.details', $item->product)}}>
                                <img alt="some" src="{{ asset($item->variant->product->mainImage->image_url ? 'storage/product-photos/' . $item->variant->product->mainImage->image_url : 'images/tshirt-noback/tshirt-logo-1.png') }}" />
                            </a>
                            <div class="flex-grow-1 ms-3">
                                <h6>{{ $item->product->name }}</h6>
                                <p class="product-id">ID: <span class="product-id-value">{{ $item->product->id }}</span></p>
                                <p>Size: {{ $item->variant->size }}</p>
                                <p>Color:
                                    <span style="display: inline-block; width: 15px; height: 15px; background-color: {{ $item->variant->color->hex_code ?? '#000' }}; border-radius: 50%; vertical-align: middle;"></span>
                                </p>
                                <p>Quantity: {{ $item->quantity }}</p>
                                <p>Price by one: €{{ number_format($item->price_by_one, 2) }}</p>
                                <p><strong>Total: €{{ number_format($item->price_by_one * $item->quantity, 2) }}</strong></p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <!-- Shipping Details Section -->
            <section class="col-lg-4 col-md-4 mb-4">
                <div class="shipping-details p-3 border">
                    <h5>Shipping Details</h5>
                    <input type="text" class="form-control mb-2" value="{{ $shipping->country }}" disabled>
                    <input type="text" class="form-control mb-2" value="{{ $shipping->city }}" disabled>
                    <input type="text" class="form-control mb-2" value="{{ $shipping->address }}" disabled>
                    <input type="text" class="form-control mb-2" value="{{ $shipping->postcode ?? '' }}" disabled>
                    <input type="text" class="form-control" value="{{ $shipping->phone }}" disabled>
                </div>
            </section>

            <!-- Order Summary Section -->
            <aside class="col-lg-4 col-md-4 mb-4">
                <div class="order-summary p-3 border">
                    <h5>Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <p>Subtotal</p>
                        <p>€{{ number_format($subtotal, 2) }}</p>
                    </div>
                    @if ($discount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <p>Discount</p>
                            <p>-€{{ number_format($discount, 2) }}</p>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <p>Delivery Fee</p>
                        <p>€{{ number_format($order->delivery_fee, 2) }}</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <p class="total">Total</p>
                        <p class="total">€{{ number_format($order->total_amount, 2) }}</p>
                    </div>
                    <div class="payment-method">
                        Payment: {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!-- Banner Section -->
    <section class="banner text-center py-4">
        <p>ENJOY EVERY MOMENT!</p>
    </section>
@endsection
