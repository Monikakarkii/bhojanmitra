@extends('backend.layout.app') <!-- Assuming you have a layout file named app.blade.php -->

@section('title', 'Edit Category')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Category</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                            <li class="breadcrumb-item active">Edit Category</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Category Details</h3>
                    </div>
                    <div class="card-body">
                        <!-- Display Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Category Edit Form -->
                        <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Category Name Field -->
                            <div class="form-group">
                                <label for="name">Category Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            </div>

                            <!-- Description Field -->
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                            </div>

                            <!-- Logo Upload Field -->
                            <div class="form-group">
                                <label for="logo">Category Logo</label>
                                @if($category->logo)
                                    <div id="image-preview">
                                        <div class="mt-2">
                                            <img src="{{ asset($category->logo) }}" alt="Current Logo" style="max-width: 150px; height: auto;">
                                        </div>
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*" onchange="previewImage(event)">
                            </div>
                            <!-- Show on Navigation Field -->
                            <div class="form-group">
                                <label for="show_on_nav">Show on Navbar</label>
                                <input type="checkbox" id="show_on_nav" name="show_on_nav" value="1" {{ old('show_on_nav', $category->show_on_nav) ? 'checked' : '' }}>
                            </div>
                            <!-- Navigation Index Field -->
                            <div class="form-group">
                                <label for="nav_index">Navbar Number</label>
                                <input type="number" class="form-control" id="nav_index" name="nav_index" value="{{ old('nav_index', $category->nav_index) }}">
                            </div>

                            <!-- Show on Home Field -->
                            <div class="form-group">
                                <label for="show_on_home">Show on Home</label>
                                <input type="checkbox" id="show_on_home" name="show_on_home" value="1" {{ old('show_on_home', $category->show_on_home) ? 'checked' : '' }}>
                            </div>

                            <!-- Home Index Field -->
                            <div class="form-group">
                                <label for="home_index">Home Index</label>
                                <input type="number" class="form-control" id="home_index" name="home_index" value="{{ old('home_index', $category->home_index) }}">
                            </div>

                            <!-- Status Field -->
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Update Category</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('customJs')
    <script>
        function previewImage(event) {
            var input = event.target;
            var preview = document.getElementById('image-preview');

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    // Remove any existing preview image
                    preview.innerHTML = '';

                    // Create a new image element for the preview
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '150px'; // Adjust as needed
                    img.style.height = 'auto'; // Adjust as needed
                    img.className = 'card-img-top';

                    // Append the new image to the preview container
                    preview.appendChild(img);
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                // If no new image is selected, hide the preview
                preview.innerHTML = '';
            }
        }
    </script>
@endsection
