@extends('layouts.layout')

@section('styles')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection
@section('content')
    <main>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif(session('error'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!-- Login Section -->
        <section class="container login-section">
            <h1>Login</h1>
            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="mb-3">
                    <input name="email" id="email" type="email" class="form-control" placeholder="Enter your email" required>
                    @error('email')
                        <span class="d-block fs-6 text-danger mt-2"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <input name="password" id="password" type="password" class="form-control" placeholder="Password" required>
                    @error('password')
                        <span class="d-block fs-6 text-danger mt-2"> {{ $message }} </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-sign-in">Sign In</button>
            </form>
            <div class="register-link">
                <p>Do you have an account? <a href="{{route('register')}}">Sign Up</a></p>
            </div>
        </section>

        <!-- Banner -->
        <section class="banner">
            <div class="container">
                <p>ENJOY EVERY MOMENT!</p>
            </div>
        </section>
    </main>
@endsection
