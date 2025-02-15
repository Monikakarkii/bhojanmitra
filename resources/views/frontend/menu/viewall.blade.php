@extends('frontend.layout.app')

@section('title', 'View All Menu Items')

@section('content')
    <style>
        .card {
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

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .card-img-top {
            height: 150px;
            object-fit: cover;
        }

        .card-body {
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }

        .card-title {
            font-weight: bold;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .card-text {
            margin-bottom: 15px;
        }

    </style>

    <div class="container mt-1 mb-auto">
        <div class="mb-3" style="display: flex;">
            <!-- Left-aligned arrow -->
            <a href="{{ url()->previous() }}" style="color: var(--primary-color); text-decoration: none;">
                <i class="fas fa-arrow-left" style="font-size: 1.4rem;"></i>
            </a>

            <!-- Center-aligned heading -->
            <h3 style="color: var(--primary-color); margin: 0 auto;">
                {{ $category->name }}
            </h3>
        </div>


        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach ($category->menuItems as $menuItem)
                <div class="col">
                    <div class="card">
                        <a href="{{route('menu.show',['menuSlug'=>$menuItem->slug])}}">
                        <img src="{{ asset($menuItem->image) }}" class="card-img-top img-fluid"
                             alt="{{ $menuItem->name }}" title="{{ $menuItem->name }}" loading="lazy">
                        </a>
                        <div class="card-body">
                            <a href="{{route('menu.show',['menuSlug'=>$menuItem->slug])}}" class="text-decoration-none">
                            <h6 class="card-title" title="{{ $menuItem->name }} "style="color: var(--primary-color);">
                                {{ \Str::limit($menuItem->name, 43) }}
                            </h6>
                            <p class="card-text" style="color: var(--text-color);">
                                <strong>Price:</strong> Rs{{ $menuItem->price }}
                            </p>
                            </a>
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
            @endforeach
        </div>
    </div>
@endsection
