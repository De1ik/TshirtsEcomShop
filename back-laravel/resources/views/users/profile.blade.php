@extends('layouts.layout')

@section('styles')
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection

@section('content')
    <main>
        <section class="profile-section container">
            <h2>Profile</h2>

            @php
                $isEditing = request()->query('edit') === 'true';
                $user = Auth::user();
                $shipping = $user->shippingInfo;
            @endphp

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                <div class="row g-3">
                    {{-- First Name --}}
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="first_name"
                               placeholder="First Name"
                               value="{{ old('first_name', $user->first_name) }}"
                            {{ $isEditing ? '' : 'readonly' }}>
                    </div>

                    {{-- Last Name --}}
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="last_name"
                               placeholder="Last Name"
                               value="{{ old('last_name', $user->last_name) }}"
                            {{ $isEditing ? '' : 'readonly' }}>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <input type="email" placeholder="Email" class="form-control" name="email"
                               value="{{ $user->email }}" readonly>
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Phone Number" name="phone"
                               value="{{ old('phone', $shipping?->phone) }}"
                            {{ $isEditing ? '' : 'readonly' }}>
                    </div>

                    {{-- Gender --}}
                    <div class="col-md-6">
                        @if($isEditing)
                            <select name="gender" class="form-control">
                                <option value="">Select Gender</option>
                                <option value="male" {{ $user->gender === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $user->gender === 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ ucfirst($user->gender) }}" readonly placeholder="Gender">
                        @endif
                    </div>

                    {{-- Country --}}
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="country" placeholder="Country"
                               value="{{ old('country', $shipping?->country) }}"
                            {{ $isEditing ? '' : 'readonly' }}>
                    </div>

                    {{-- City --}}
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="city" placeholder="City"
                               value="{{ old('city', $shipping?->city) }}"
                            {{ $isEditing ? '' : 'readonly' }}>
                    </div>

                    {{-- Zip Code --}}
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="zip_code" placeholder="Zip Code"
                               value="{{ old('zip_code', $shipping?->zip_code) }}"
                            {{ $isEditing ? '' : 'readonly' }}>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-4">
                    @if($isEditing)
                        <button type="submit" class="btn btn-success">Save Changes</button>
                        <a href="{{ route('profile') }}" class="btn btn-secondary">Cancel</a>
                    @else
                        <a href="{{ route('profile', ['edit' => 'true']) }}" class="btn btn-primary">Edit Profile</a>
                    @endif

                    <a href="{{ route('logout') }}" class="btn btn-danger ms-3"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Log Out
                    </a>
                </div>
            </form>

            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
        </section>

        <!-- Orders Section -->
        <section class="orders-section container my-4">
            <h2>All Orders</h2>
            <div class="order-list">
                <article  class="order-item d-flex justify-content-between align-items-center border p-3 mb-3">
                    <div class="order-details">
                        <p><strong>Order #100</strong></p>
                        <p>Status: Not Delivered</p>
                        <p>Components: 4</p>
                        <p>€75</p>
                    </div>
                    <div class="order-status text-end">
                        <span class="status not-delivered d-block">Payment after delivery</span>
                        <a href="../order/submitted_order.html">
                            <button class="details-btn btn btn-primary">Details</button>
                        </a>
                    </div>
                </article>
                <article  class="order-item d-flex justify-content-between align-items-center border p-3 mb-3">
                    <div class="order-details">
                        <p><strong>Order #101</strong></p>
                        <p>Status: Not Delivered</p>
                        <p>Components: 4</p>
                        <p>€100</p>
                    </div>
                    <div class="order-status text-end">
                        <span class="status delivered d-block">Paid successfully</span>
                        <a href="#">
                            <button class="details-btn btn btn-primary">Details</button>
                        </a>
                    </div>
                </article>
            </div>
        </section>

        <div class="banner" role="banner">
            ENJOY EVERY MOMENT!
        </div>
    </main>
@endsection
