@extends('frontend.layout.app') <!-- Assuming you have a layout file named app.blade.php -->

@section('title', 'Home') <!-- Page Title -->

@section('content')
    <main class="flex-grow-1 d-flex flex-column align-items-center justify-content-center p-4 mb-auto">
        <div class="main-content">
            <h2>Welcome to {{ websiteInfo()->app_name }}</h2>

            <!-- Display dynamic quote -->
            <p class="quote-text">
                "{{ websiteinfo()->quote }}" <!-- Dynamic Quote from websiteinfo() -->
            </p>

            <p class="text-muted-foreground mb-4" style="max-width: 600px;">
                Enjoy delicious food from our menu. Scan the QR code on your table to start your meal.
            </p>
            <a href="#" class="btn btn-primary btn-md">
                <i class="fas fa-qrcode me-2"></i> Scan QR Code
            </a>
        </div>
    </main>
@endsection
