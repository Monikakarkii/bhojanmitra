@extends('frontend.layout.app')

@section('title', $menuItem->name)

@section('content')
    <style>
        .card1 {
            background-color: var(--background-color);
            border: 1px solid var(--muted-color);
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card1:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .card-img-top {
            height: 150px;
            object-fit: cover;
        }

        .card-body1 {
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }

        .card-title1 {
            font-weight: bold;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .card-text1 {
            margin-bottom: 15px;
        }

    </style>
    <div class="container mt-auto mb-2">
        <div class="mb-3 d-flex align-items-center">
            <!-- Left-aligned arrow -->
            <a href="{{ url()->previous() }}" style="color: var(--primary-color); text-decoration: none; margin-right: 15px;">
                <i class="fas fa-arrow-left" style="font-size: 1.4rem;"></i>
            </a>
            <h6 class="text-center m-0">Description of {{ $menuItem->name }}</h6>
        </div>
        <div class="card mb-3" style="max-width: 540px; background-color: var(--background-color);">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="{{ asset($menuItem->image) }}" class="img-fluid rounded-start" alt="{{ $menuItem->name }}">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title1">{{ $menuItem->name }}</h5>
                        <p class="card-text">{{ $menuItem->description }}</p>
                        <p class="card-text">
                            <strong>Price:</strong> Rs{{ $menuItem->price }}
                        </p>

                        <!-- Tags Section -->
                        <div class="d-flex align-items-center m-1">
                            <i class="fas fa-tag me-2"></i>
                            @foreach($menuItem->tags as $tag)
                                <span class="badge bg-secondary mx-1">{{ $tag->name }}</span>
                            @endforeach
                        </div>

                        <a href="javascript:void(0);"
                           class="btn btn-primary w-100 add-to-cart"
                           data-id="{{ $menuItem->id }}"
                           data-name="{{ $menuItem->name }}"
                           data-price="{{ $menuItem->price }}"
                           data-image="{{ $menuItem->image }}">
                            Add to Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Menu Items Section -->
        <div class="mt-5 ">
            <h5>Related Menu Items</h5>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach($menuItem->categories as $category)
                    @foreach($category->menuItems->take(4) as $relatedMenuItem)
                        @if($relatedMenuItem->id !== $menuItem->id) <!-- Skip the current menu item -->
                        <div class="col">
                            <div class="card1">
                                <a href="{{ route('menu.show', ['menuSlug' => $relatedMenuItem->slug]) }}">
                                    <img src="{{ asset($relatedMenuItem->image) }}" class="card-img-top img-fluid"
                                         alt="{{ $relatedMenuItem->name }}">
                                </a>
                                <div class="card-body1 p-2">
                                    <a href="{{ route('menu.show', ['menuSlug' => $relatedMenuItem->slug]) }}" class="text-decoration-none">
                                        <h6 class="card-title1" title="{{ $relatedMenuItem->name }}"style="color: var(--primary-color);">
                                            {{ \Str::limit($relatedMenuItem->name, 43) }}
                                        </h6>
                                    </a>
                                    <p class="card-text1" style="color: var(--text-color);">
                                        <strong>Price:</strong> Rs{{ $relatedMenuItem->price }}
                                    </p>
                                    <a href="javascript:void(0);"
                                       class="btn btn-primary w-100 add-to-cart"
                                       data-id="{{ $relatedMenuItem->id }}"
                                       data-name="{{ $relatedMenuItem->name }}"
                                       data-price="{{ $relatedMenuItem->price }}"
                                       data-image="{{ $relatedMenuItem->image }}">
                                        Add to Cart
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>

    </div>
@endsection
