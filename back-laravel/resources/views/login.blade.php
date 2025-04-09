@extends('layouts.layout')

@section('styles')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection
@section('content')
    <main>
        <!-- Login Section -->
        <section class="container login-section">
            <h1>Login</h1>
            <form action="" method="post">
                <div class="mb-3">
                    <input type="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-sign-in">Sign In</button>
            </form>
            <div class="register-link">
                <p>Do you have an account? <a href="./registration.html">Sign Up</a></p>
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
