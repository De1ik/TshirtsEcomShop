@extends('layouts.layout')

@section('styles')
    <link href="{{ asset('css/shipping.css') }}" rel="stylesheet">
@endsection

@section('content')
    <main class="container my-5">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart') }}">Cart</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shipping</li>
            </ol>
        </nav>

        <h2 class="mb-4">Shipping</h2>
        <div class="row">
            <form action="{{ route('checkout.store') }}" method="POST" class="d-flex flex-wrap">
                @csrf

                <!-- Shipping Form Section -->
                <section class="col-lg-8 col-md-7 mb-4 pe-lg-4">
                    <div class="shipping-form">
                        <h5>Shipping Information</h5>

                        @guest
                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Enter your email"
                                       value="{{ old('email','') }}">
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        @else
                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}" disabled>
                            </div>
                        @endguest

                        <div class="mb-3">
                            <input type="text" class="form-control" name="country" placeholder="Enter your country"
                                   value="{{ old('country', $shipping_info->country ?? '') }}">
                            @error('country') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" name="city" placeholder="Enter your town"
                                   value="{{ old('city', $shipping_info->city ?? '') }}">
                            @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" name="address" placeholder="Enter your address"
                                   value="{{ old('address', $shipping_info->address ?? '') }}">
                            @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" name="postcode" placeholder="Enter your postcode"
                                   value="{{ old('postcode') }}">
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" name="phone" placeholder="Enter your phone number"
                                   value="{{ old('phone', $shipping_info->phone ?? '') }}">
                            @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </section>

                <!-- Order Summary Aside -->
                <aside class="col-lg-4 col-md-5">
                    @php
                        $subtotal = 0;
                        $discount = 0;

                        $items = isset($cart->items) ? $cart->items : $cart;

                        foreach ($items as $item) {
                            if (isset($item->unit_price)) {
                                // Logged-in user
                                $original = $item->unit_price;
                                $discounted = $item->variant->product->activeDiscount?->new_price ?? $original;
                                $quantity = $item->quantity;
                            } else {
                                // Guest user
                                $original = $item['unit_price'];
                                $discounted = $original;
                                $quantity = $item['quantity'];
                            }

                            $subtotal += $original * $quantity;
                            $discount += ($original - $discounted) * $quantity;
                        }
                        $delivery = match($delivery_method ?? 'courier') {
                            'packeta' => 4,
                            'mail' => 3,
                            default => 5,
                        };

                        $total = $subtotal - $discount + $delivery;
                    @endphp

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
                            <p>Delivery Fee ({{ ucfirst($delivery_method) }})</p>
                            <p>€{{ number_format($delivery, 2) }}</p>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <p class="total">Total</p>
                            <p class="total">€{{ number_format($total, 2) }}</p>
                        </div>

                        <input type="hidden" name="payment_method" id="paymentMethod" value="">

                        <button type="submit" onclick="submitWithPayment('cash')" class="btn btn-primary d-block w-100 mb-2">
                            Pay at Delivery (Cash)
                        </button>

                        <button type="submit" onclick="submitWithPayment('google_pay')" class="btn btn-dark d-block w-100 mb-2">
                            Pay with Google Pay
                        </button>

                        <button type="submit" onclick="submitWithPayment('apple_pay')" class="btn btn-secondary d-block w-100 mb-2">
                            Pay with Apple Pay
                        </button>

                        <button type="submit" onclick="submitWithPayment('paypal')" class="btn btn-outline-primary d-block w-100">
                            Pay with PayPal
                        </button>
                    </div>
                </aside>
            </form>
        </div>
    </main>

    <!-- Banner Section -->
    <section class="banner text-center py-4">
        <p>ENJOY EVERY MOMENT!</p>
    </section>
@endsection

@section('scripts')
    <script>
        function submitWithPayment(method) {
            document.getElementById('paymentMethod').value = method;
        }
    </script>
@endsection
