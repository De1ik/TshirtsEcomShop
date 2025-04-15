@extends('layouts.layout')

@section('styles')
    <link href="{{assert("css/cart.css")}}" rel="stylesheet">
@endsection
@section('content')
    <main class="container my-5">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cart</li>
            </ol>
        </nav>

        <h2 class="mb-4">Your Cart</h2>
        <div class="row">
            <!-- Cart Items Section -->
            <section class="col-lg-8 col-md-7 mb-4">
                <!-- Cart Item 1 -->
                <article class="cart-item d-flex align-items-center mb-3">
                    <a href="../product/product_details.html">
                        <img src="../../../images/tshirt-noback/tshirt-logo-1.png" alt="Top T-shirt">
                    </a>
                    <div class="flex-grow-1 ms-3">
                        <h6>Top Hoodie</h6>
                        <p class="product-id">ID: <span class="product-id-value">12345</span></p>
                        <p>Size: S</p>
                        <p>Color: <span style="display: inline-block; width: 15px; height: 15px; background-color: #000; border-radius: 50%; vertical-align: middle;"></span></p>
                        <p>€60</p>
                    </div>
                    <div class="quantity-selector me-3">
                        <button type="button">-</button>
                        <input type="text" value="1" readonly>
                        <button type="button">+</button>
                    </div>
                    <button class="remove-btn btn btn-link" type="button">
                        <i class="bi bi-trash"></i>
                    </button>
                </article>
                <!-- Cart Item 2 -->
                <article class="cart-item d-flex align-items-center mb-3">
                    <a href="../product/product_details.html">
                        <img src="../../../images/tshirt-noback/tshirt-logo-1.png" alt="Top T-shirt">
                    </a>
                    <div class="flex-grow-1 ms-3">
                        <h6>Top Hoodie</h6>
                        <p class="product-id">ID: <span class="product-id-value">12345</span></p>
                        <p>Size: M</p>
                        <p>Color: <span style="display: inline-block; width: 15px; height: 15px; background-color: #000; border-radius: 50%; vertical-align: middle;"></span></p>
                        <p>€40</p>
                    </div>
                    <div class="quantity-selector me-3">
                        <button type="button">-</button>
                        <input type="text" value="1" readonly>
                        <button type="button">+</button>
                    </div>
                    <button class="remove-btn btn btn-link" type="button">
                        <i class="bi bi-trash"></i>
                    </button>
                </article>
            </section>
            <!-- Order Summary Section -->
            <aside class="col-lg-4 col-md-5">
                <div class="order-summary p-3 border">
                    <h5>Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <p>Subtotal</p>
                        <p>€100</p>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <p>Discount (-30%)</p>
                        <p>-€30</p>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <p>Delivery Fee</p>
                        <p>€5</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <p class="total">Total</p>
                        <p class="total">€75</p>
                    </div>
                    <a href="./shipping.html" class="go-to-shipping btn btn-primary d-block text-center">Go to Shipping</a>
                </div>
            </aside>
        </div>
    </main>

    <!-- Banner Section -->
    <section class="banner text-center py-4">
        <p>ENJOY EVERY MOMENT!</p>
    </section>
@endsection
@section('scripts')
    <script>
        document.querySelectorAll('.quantity-selector').forEach(selector => {
            const input = selector.querySelector('input');
            const minusButton = selector.querySelector('button:first-of-type');
            const plusButton = selector.querySelector('button:last-of-type');

            minusButton.addEventListener('click', () => {
                let value = parseInt(input.value);
                if (value > 1) input.value = value - 1;
            });

            plusButton.addEventListener('click', () => {
                let value = parseInt(input.value);
                input.value = value + 1;
            });
        });
    </script>
@endsection
