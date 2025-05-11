@extends('layouts.layout')

@section('styles')
    <link href="{{ asset('css/registration.css') }}" rel="stylesheet">
@endsection
@section('content')
    <main>
        <!-- Registration Section -->
        <section class="container registration-section">
            <h1>Registration</h1>
            <form action="{{ route('register') }}" method="post">
                @csrf
                <div class="mb-3">
                    <input name="email" id="email" type="email" class="form-control" placeholder="Enter your email" required>
                    @error('email')
                        <span class="d-block fs-6 text-danger mt-2"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    @error('password')
                        <span class="d-block fs-6 text-danger mt-2"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <input name="password_confirmation" id="confirm-password" type="password" class="form-control" placeholder="Confirm password" required>
                    @error('password_confirmation')
                    <span class="d-block fs-6 text-danger mt-2"> {{ $message }} </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-sign-up">Sign Up</button>
            </form>
            <div class="login-link">
                <p>Already have an account? <a href="{{route('login')}}">Login Now!</a></p>
            </div>
        </section>

    </main>

    <!-- Banner Section -->
    <section class="banner">
        <div class="container">
            <p>ENJOY EVERY MOMENT!</p>
        </div>
    </section>
@endsection
