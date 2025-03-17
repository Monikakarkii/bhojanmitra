@extends('frontend.layout.app')

@section('title', 'Menu')

@section('content')
    <style>
    .category-name {
    position: relative;
    text-decoration: none;
    color: var(--text-color);
    font-weight: bold;
    white-space: nowrap;
    font-size: 1.2rem;
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    transition: all 0.3s ease;
    background: var(--background-color);
}

.category-name:hover,
.category-name.selected {
    background: var(--primary-color);
    color: #fff;
}

.card {
    background-color: var(--background-color);
    border: 1px solid var(--muted-color);
    border-radius: 15px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.card-img-top {
    height: 130px;
    object-fit: cover;
}

.card-body {
    color: var(--text-color);
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Ensures no extra gaps */
    flex-grow: 1;
    padding: 12px; /* Adjusted for better spacing */
}

.card-title {
    font-weight: bold;
    font-size: 1rem;
    margin-bottom: 5px; /* Reduced gap */
}

.card-text {
    margin-bottom: 5px; /* Reduced space between price and button */
}

/* Ensures all cards inside the Owl Carousel have the same height */
.owl-carousel .item .card {
    display: flex;
    flex-direction: column;
    min-height: 280px; /* Adjusted for better responsiveness */
    height: 100%;
}

.owl-carousel .item .card-body {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.owl-carousel .item .card-body .add-to-cart {
    margin-top: auto; /* Keeps the button at the bottom */
}

/* Category Slider */
.category-slider {
    display: flex;
    align-items: center;
    gap: 10px;
    overflow-x: auto;
    white-space: nowrap;
    scrollbar-width: none;
}

.category-slider::-webkit-scrollbar {
    display: none;
}

.category-container {
    margin-bottom: 40px;
}

/* Hides navigation arrows in Owl Carousel */
.owl-carousel .owl-nav {
    display: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-body {
        padding: 10px;
    }

    .card-title {
        font-size: 0.95rem;
    }

    .card-text {
        font-size: 0.9rem;
        margin-bottom: 4px;
    }

    .owl-carousel .item .card {
        min-height: 260px;
    }
}

@media (max-width: 480px) {
    .card-body {
        padding: 8px;
    }

    .card-title {
        font-size: 0.9rem;
    }

    .card-text {
        font-size: 0.85rem;
        margin-bottom: 3px;
    }

    .owl-carousel .item .card {
        min-height: 250px;
    }

    .add-to-cart {
        font-size: 14px;
        padding: 6px;
    }
}

    </style>

    <div class="container mt-3 bg-background mb-auto" style="color: var(--text-color);">
        <!-- Category Slider -->
        <div class="category-slider mb-4">
            <a href="{{ route('menu.home', ['tableNumber' => $tableNumber]) }}" class="category-name" id="all-category">
                All
            </a>
            @foreach ($navCategories as $category)
                <a href="{{ route('menu.home', ['tableNumber' => $tableNumber, 'category' => $category->slug]) }}"
                    class="category-name" data-category="{{ $category->slug }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <!-- Menu Items -->
        <div class="container mt-4">
            @foreach ($homeCategories as $category)
                @if ($category->menuItems->isNotEmpty())
                    <div class="category-container">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
                            <h5 class="text-left">{{ $category->name }}</h5>
                            <a href="{{ route('menu.viewAll', ['categorySlug' => $category->slug]) }}">View all</a>
                        </div>

                        @if (!$categorySlug)
                            <!-- Owl Carousel -->
                            <div class="owl-carousel owl-theme">
                                @foreach ($category->menuItems as $menuItem)
                                    <div class="item">
                                        <div class="card">
                                            <a href="{{ route('menu.show', ['menuSlug' => $menuItem->slug]) }}">
                                                <img src="{{ asset($menuItem->image) }}" class="card-img-top img-fluid"
                                                    alt="{{ $menuItem->name }}" loading="lazy">
                                            </a>
                                            <div class="card-body d-flex flex-column">
                                                <a href="{{ route('menu.show', ['menuSlug' => $menuItem->slug]) }}" class="text-decoration-none">
                                                    <h6 class="card-title" style="color: var(--primary-color); margin-bottom: 0;">
                                                        {{ \Illuminate\Support\Str::limit($menuItem->name, 43) }}
                                                    </h6>
                                                </a>
                                                <p class="card-text mb-2"><strong>Price:</strong> Rs{{ $menuItem->price }}</p>
                                                <button class="btn btn-primary w-100 add-to-cart mt-auto"
                                                    data-id="{{ $menuItem->id }}" data-name="{{ $menuItem->name }}"
                                                    data-price="{{ $menuItem->price }}" data-image="{{ $menuItem->image }}">
                                                    Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Grid View -->
                            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                                @foreach ($category->menuItems as $menuItem)
                                    <div class="col">
                                        <div class="card">
                                            <a href="{{ route('menu.show', ['menuSlug' => $menuItem->slug]) }}">
                                                <img src="{{ asset($menuItem->image) }}" class="card-img-top img-fluid"
                                                    alt="{{ $menuItem->name }}" loading="lazy">
                                            </a>
                                            <div class="card-body d-flex flex-column">
                                                <a href="{{ route('menu.show', ['menuSlug' => $menuItem->slug]) }}" class="text-decoration-none">
                                                    <h6 class="card-title" style="color: var(--primary-color); margin-bottom: 4;">
                                                        {{ \Illuminate\Support\Str::limit($menuItem->name, 43) }}
                                                    </h6>
                                                </a>
                                                <p class="card-text mb-2"><strong>Price:</strong> Rs{{ $menuItem->price }}</p>
                                                <button class="btn btn-primary w-100 add-to-cart mt-auto"
                                                    data-id="{{ $menuItem->id }}" data-name="{{ $menuItem->name }}"
                                                    data-price="{{ $menuItem->price }}" data-image="{{ $menuItem->image }}">
                                                    Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $(".owl-carousel").owlCarousel({
                items: 3,
                margin: 5,
                responsive: {
                    0: {
                        items: 2
                    },
                    768: {
                        items: 3
                    },
                    1024: {
                        items: 4
                    }
                }
            });

            $(".category-name").on("click", function() {
                $(".category-name").removeClass("selected");
                $(this).addClass("selected");
            });

            const currentCategorySlug = "{{ $categorySlug }}";
            if (!currentCategorySlug) {
                $('#all-category').addClass('selected');
            } else {
                $(`.category-name[data-category='${currentCategorySlug}']`).addClass('selected');
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const userToken = localStorage.getItem('user_token');
            if (userToken) {
                const userTokenUrl = "{{ route('menu.user.token') }}?token=" + userToken;
                fetch(userTokenUrl)
                    .then(response => response.json())
                    .then(data => console.log(data.message))
                    .catch(console.error);
            } else {
                console.log("token not found");
                const userTokenUrl = "{{ route('menu.user.token') }}";
                fetch(userTokenUrl)
                    .then(response => response.json())
                    .then(data => {
                        localStorage.setItem('user_token', data.token);
                        console.log(data.message);
                    })
                    .catch(console.error);
            }
        });
    </script>
@endsection
