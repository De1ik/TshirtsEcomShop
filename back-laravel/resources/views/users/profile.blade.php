@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.layout')

@section('styles')
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection

@section('content')
    <main>
        <section class="profile-section container">
            <h2>Profile</h2>

            @php
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
                        <div class="form-group position-relative">
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name"
                                   value="{{ old('first_name', $user->first_name) }}" readonly>
                            <button type="button" class="edit-btn position-absolute top-0 end-0 mt-1 me-1"
                                    onclick="toggleEdit('first_name')">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Last Name --}}
                    <div class="col-md-6">
                        <div class="form-group position-relative">
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name"
                                   value="{{ old('last_name', $user->last_name) }}" readonly>
                            <button type="button" class="edit-btn position-absolute top-0 end-0 mt-1 me-1"
                                    onclick="toggleEdit('last_name')">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Email (Read-only) --}}
                    <div class="col-md-6">
                        <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                        <button type="button" class="edit-btn position-absolute top-0 end-0 mt-1 me-1"
                                onclick="toggleEdit('email')">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <div class="form-group position-relative">
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number"
                                   value="{{ old('phone', $shipping?->phone) }}" readonly>
                            <button type="button" class="edit-btn position-absolute top-0 end-0 mt-1 me-1"
                                    onclick="toggleEdit('phone')">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Gender --}}
                    <div class="col-md-6">
                        <div class="form-group position-relative">
                            <select name="gender" id="gender" class="form-control" disabled>
                                <option value="">Select Gender</option>
                                <option value="male" {{ $user->gender === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $user->gender === 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            <button type="button" class="edit-btn position-absolute top-0 end-0 mt-1 me-1"
                                    onclick="toggleSelect('gender')">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Country --}}
                    <div class="col-md-6">
                        <div class="form-group position-relative">
                            <input type="text" class="form-control" id="country" name="country" placeholder="Country"
                                   value="{{ old('country', $shipping?->country) }}" readonly>
                            <button type="button" class="edit-btn position-absolute top-0 end-0 mt-1 me-1"
                                    onclick="toggleEdit('country')">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>

                    {{-- City --}}
                    <div class="col-md-6">
                        <div class="form-group position-relative">
                            <input type="text" class="form-control" id="city" name="city" placeholder="City"
                                   value="{{ old('city', $shipping?->city) }}" readonly>
                            <button type="button" class="edit-btn position-absolute top-0 end-0 mt-1 me-1"
                                    onclick="toggleEdit('city')">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Zip Code --}}
                    <div class="col-md-6">
                        <div class="form-group position-relative">
                            <input type="text" class="form-control" id="zip_code" name="zip_code"
                                   value="{{ old('zip_code', $shipping?->zip_code) }}" placeholder="Zip Code" readonly>
                            <button type="button" class="edit-btn position-absolute top-0 end-0 mt-1 me-1"
                                    onclick="toggleEdit('zip_code')">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Save Changes</button>

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
                @forelse($orders as $order)
                    <article class="order-item d-flex justify-content-between align-items-center border p-3 mb-3">
                        <div class="order-details">
                            <p><strong>Order #{{ $order->id }}</strong></p>
                            <p>Status: {{ ucfirst($order->status) }}</p>
                            <p>Components: {{ $order->items->count() }}</p>
                            <p>€{{ number_format($order->total_amount, 2) }}</p>
                        </div>
                        <div class="order-status text-end">
                    <span class="status {{ $order->payment?->payment_status === 'paid' ? 'delivered' : 'not-delivered' }} d-block">
                        {{ $order->payment?->payment_status === 'paid' ? 'Paid successfully' : 'Payment after delivery' }}
                    </span>
                            <a href="{{route('order.details', $order->id)}}">
                                <button class="details-btn btn btn-primary">Details</button>
                            </a>
                        </div>
                    </article>
                @empty
                    <p>No orders found.</p>
                @endforelse
            </div>
        </section>

        <div class="banner" role="banner">
            ENJOY EVERY MOMENT!
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        function toggleEdit(fieldId) {
            const input = document.getElementById(fieldId);
            const button = input.nextElementSibling;

            if (input.readOnly) {
                input.readOnly = false;
                input.style.backgroundColor = '#fff';
                button.innerHTML = '<i class="bi bi-check-circle confirm-btn"></i>';
            } else {
                input.readOnly = true;
                input.style.backgroundColor = '#f9f9f9';
                button.innerHTML = '<i class="bi bi-pencil edit-btn"></i>';
            }
        }

        function toggleSelect(selectId) {
            const select = document.getElementById(selectId);
            const button = select.nextElementSibling;

            if (select.disabled) {
                select.disabled = false;
                select.style.backgroundColor = '#fff';
                button.innerHTML = '<i class="bi bi-check-circle confirm-btn"></i>';
            } else {
                select.disabled = true;
                select.style.backgroundColor = '#f9f9f9';
                button.innerHTML = '<i class="bi bi-pencil edit-btn"></i>';
            }
        }
    </script>
@endsection
