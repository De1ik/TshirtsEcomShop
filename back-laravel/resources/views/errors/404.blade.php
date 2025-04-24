@extends('layouts.layout')

@section('title', 'Page Not Found')

@section('styles')
    <style>
        .error-page {
            text-align: center;
            padding: 100px 20px;
        }

        .error-page h1 {
            font-size: 6rem;
            font-weight: 700;
        }

        .error-page p {
            font-size: 1.25rem;
            margin-bottom: 30px;
        }
    </style>
@endsection

@section('content')
    <div class="container error-page">
        <h1>404</h1>
        <p>Oops! The page you're looking for doesn't exist.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">
            <i class="bi bi-house-door"></i> Go to Main Page
        </a>
    </div>

    <div class="banner" role="banner">
        ENJOY EVERY MOMENT!
    </div>
@endsection
