@extends('layouts.layout')

@section('title', 'Unauthorized')

@section('content')
    <div class="container text-center my-5">
        <h1 class="display-4 text-danger">403 - Unauthorized</h1>
        <p class="lead">You do not have permission to access this page.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Return to Home</a>
    </div>
@endsection
