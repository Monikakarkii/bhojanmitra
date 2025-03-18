<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ websiteInfo()->app_name }} - @yield('title', 'Default Title')</title>
    <link rel="icon" type="image/png" href="{{ websiteInfo() && websiteInfo()->first() && websiteInfo()->app_logo ? asset('app_logo/' . websiteInfo()->app_logo) : asset('default/website.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{asset('adminlte/plugins/owlcarousel2/assets/owl.carousel.css')}}"  />
    <link rel="stylesheet" href="{{asset('adminlte/plugins/owlcarousel2/assets/owl.theme.default.min.css')}}"  />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- Add Playfair Display font -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;700&display=swap" rel="stylesheet">


    <style>
        /* Define default theme variables */
        :root {
            --primary-color: {{ websiteinfo()->theme_color_primary }};
            --background-color: #FFFFFF;
            --text-color: #212529;
            --muted-color: #6c757d;
        }

        /* Dark mode theme variables */
        [data-theme="dark"] {
            --primary-color: {{ websiteinfo()->theme_color_primary }};
            --background-color: #18191a;
            --text-color: #e4e6eb;
            --muted-color: #b0b3b8;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Jost', sans-serif; /* Apply Jost font */
        }

        .bg-background {
            background-color: var(--background-color);
        }

        .text-primary {
            color: var(--primary-color);
        }

        .text-muted-foreground {
            color: var(--muted-color);
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            outline: none;
            box-shadow: none;
        }

        .btn-primary:focus, .btn-primary:active, .btn-primary:hover {
            text-decoration: none;
        }

        /* Logo & App Name Enhancements */
        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .logo-section img {
            max-width: 70px;
            max-height: 70px;
            border-radius: 50%;
            border: 3px solid var(--primary-color);
            padding: 5px;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .logo-section h1 {
            font-size: 2rem;
            font-weight: 700;
            font-family: 'Jost', sans-serif; /* Apply Jost font */
            color: var(--text-color);
            margin-bottom: 0;
        }

        .main-content h2 {
            font-size: 2rem;
            font-weight: 600;
            font-family: 'Jost', sans-serif; /* Apply Jost font */
        }

        .quote-text {
            font-style: italic;
            font-family: 'Jost', sans-serif; /* Apply Jost font */
            color: var(--muted-color);
            margin-top: 20px;
        }

        /* Cart and Button Fixed to the right side */
        .cart-button-wrapper {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 999;
        }

        .cart-icon {
            position: relative;
            font-size: 1.5rem;
            color: var(--primary-color);
            padding: 15px;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .cart-icon .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            padding: 5px 10px;
            border-radius: 50%;
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 bg-background" data-theme="light">
@include('frontend.layout.navbar')
<!-- Header -->

<!-- Main Content -->
@yield('content')

<!-- Cart and Button Fixed to Right -->
@if (session('user_table') && session('user_table_token') && Route::currentRouteName() !== 'menu.cart.view')
    <div class="cart-button-wrapper">
        <a href="{{route('menu.cart.view')}}" class="cart-icon">
            <i class="fas fa-shopping-cart"></i>
            <span class="badge" id="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
        </a>
    </div>
@endif

<!-- Footer -->
@include('frontend.layout.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('adminlte/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{asset('adminlte/plugins/owlcarousel2/owl.carousel.min.js')}}" ></script>

<script>
    // Notification logic
    $(document).ready(function() {
        // Check for flashed session message and show Toastr notification
        @if(session()->has('success'))
        toastr.success('{{ session('success') }}');
        @elseif(session()->has('error'))
        toastr.error('{{ session('error') }}');
        @endif
    });
</script>
<script>
    // Theme toggle logic
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const body = document.body;

    function applyTheme(theme) {
        body.setAttribute('data-theme', theme);
        if (theme === 'dark') {
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
        } else {
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        }
    }

    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        applyTheme(savedTheme);
    }

    themeToggle.addEventListener('click', function () {
        const currentTheme = body.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        applyTheme(newTheme);
        localStorage.setItem('theme', newTheme);
    });
    $(document).on('click', '.add-to-cart', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const price = $(this).data('price');
        const image = $(this).data('image');

        $.ajax({
            url: "{{ route('menu.cart.add') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                name: name,
                price: price,
                quantity: 1,
                image: image
            },
            success: function (response) {
                // Update cart count in the header
                $('#cart-count').text(response.cartCount);
            }
        });
    });
</script>
@yield('js')
</body>
</html>
