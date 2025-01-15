@extends('backend.layout.app')

@section('title', 'Edit Menu Item')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Menu Item</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('menu-items.index') }}">Menu Items</a></li>
                            <li class="breadcrumb-item active">Edit Menu Item</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Edit Menu Item</h3>
                    </div>
                    <div class="card-body">
                        <!-- Flash Messages -->
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Form -->
                        <form action="{{ route('menu-items.update', $menuItem->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Name -->
                                <div class="form-group col-md-6">
                                    <label for="name">Menu Item Name</label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter Menu Item Name" value="{{ old('name', $menuItem->name) }}" required>
                                </div>

                                <!-- Price -->
                                <div class="form-group col-md-6">
                                    <label for="price">Price</label>
                                    <input type="number" id="price" name="price" class="form-control" placeholder="Enter Price" value="{{ old('price', $menuItem->price) }}" step="0.01" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Categories -->
                                <div class="form-group col-md-6">
                                    <label for="categories">Categories</label>
                                    <select id="categories" name="categories[]" class="form-control select2" multiple="multiple" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $menuItem->categories->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tags -->
                                <div class="form-group col-md-6">
                                    <label for="tags">Tags</label>
                                    <input type="text" id="tags" name="tags" class="form-control tokenfield" placeholder="Add Tags for this Food..." value="{{ old('tags', implode(',', $menuItem->tags->pluck('name')->toArray())) }}">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Availability -->
                                <div class="form-group col-md-6">
                                    <label for="availability">Availability</label>
                                    <select id="availability" name="availability" class="form-control">
                                        <option value="1" {{ old('availability', $menuItem->availability) == '1' ? 'selected' : '' }}>Available</option>
                                        <option value="0" {{ old('availability', $menuItem->availability) == '0' ? 'selected' : '' }}>Out of Stock</option>
                                    </select>
                                </div>

                                <!-- Image -->
                                <div class="form-group col-md-6">
                                    <label for="image">Image</label>
                                    <input type="file" id="image" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                                    <div id="image-preview">
                                        @if($menuItem->image)
                                            <img src="{{ asset($menuItem->image) }}" alt="Menu Image" class="img-thumbnail" width="150">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Description -->
                            <div class="form-group">
                                <label for="description">Short Description</label>
                                <textarea id="description" name="description" class="form-control" placeholder="Enter a brief description of the food item">{{ old('description', $menuItem->description) }}</textarea>
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="btn btn-success">Update Menu Item</button>
                            <a href="{{ route('menu-items.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('customCss')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .alert {
            margin-top: 15px;
        }

        #image-preview img {
            max-width: 150px;
            max-height: 150px;
        }

        #categories {
            border: 1px solid #659acc;
            border-radius: 0.25rem;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('customJs')
    <script>
        // Initialize Select2
        $('#categories').select2({
            placeholder: 'Select Categories',
            allowClear: true
        });

        // Prevent duplicate categories
        $('#categories').on('change', function () {
            const selectedCategories = $(this).val();
            const uniqueCategories = [...new Set(selectedCategories)];

            if (uniqueCategories.length !== selectedCategories.length) {
                $(this).val(uniqueCategories).trigger('change');
                displayFlashMessage('Duplicate categories are not allowed!');
            }
        });

        // Initialize Tokenfield
        $('#tags').tokenfield({
            limit: 10,
            createTokensOnBlur: true
        }).on('tokenfield:createtoken', function (e) {
            const existingTokens = $(this).tokenfield('getTokens');
            let duplicateFound = false;

            $.each(existingTokens, function (index, token) {
                if (token.value === e.attrs.value) {
                    e.preventDefault();
                    duplicateFound = true;
                }
            });

            if (duplicateFound) {
                displayFlashMessage('Duplicate tags are not allowed!');
            }
        });

        // Display Flash Message
        function displayFlashMessage(message) {
            const flashContainer = $('<div class="alert alert-danger"></div>').text(message);
            $('.card-body').prepend(flashContainer);

            setTimeout(() => {
                flashContainer.fadeOut(() => flashContainer.remove());
            }, 3000);
        }

        // Image Preview
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('image-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.innerHTML = ''; // Clear previous preview
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    preview.appendChild(img);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
