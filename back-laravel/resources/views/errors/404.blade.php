@extends('layouts.layout')

@section('title', 'Page Not Found')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/404.css')}}">
@endsection

@section('content')
    <div class="container error-page">
        <h1>404</h1>
        <p>Oops! The page you're looking for doesn't exist.</p>
        <a href="{{ route('home') }}" class="btn main-btn">
            <i class="bi bi-house-door"></i> Go to Main Page
        </a>
    </div>

    <div class="banner" role="banner">
        ENJOY EVERY MOMENT!
    </div>
@endsection
