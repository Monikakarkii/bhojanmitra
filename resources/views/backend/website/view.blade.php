@extends('backend.layout.app') <!-- Assuming you have a layout file named app.blade.php -->

@section('title', 'Dashboard')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Site Setting</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Site Setting</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Settings</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!-- Form -->
                        <form action="{{ route('site-settings.create') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" class="form-control" name="id"
                                    value="{{ $siteSetting->id ?? '' }}">

                                <!-- App Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="app_name">App Name</label>
                                        <input type="text" class="form-control" id="app_name" name="app_name"
                                            value="{{ old('app_name', $siteSetting->app_name ?? '') }}" required>
                                    </div>
                                </div>

                                <!-- Quote -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quote">Quote</label>
                                        <input type="text" class="form-control" id="quote" name="quote"
                                            value="{{ old('quote', $siteSetting->quote ?? '') }}">
                                    </div>
                                </div>

                                <!-- Location -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" class="form-control" id="location" name="location"
                                            value="{{ old('location', $siteSetting->location ?? '') }}">
                                    </div>
                                </div>

                                <!-- Contact Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_number">Contact Number</label>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number"
                                            value="{{ old('contact_number', $siteSetting->contact_number ?? '') }}">
                                    </div>
                                </div>

                                <!-- Contact Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_email">Contact Email</label>
                                        <input type="email" class="form-control" id="contact_email" name="contact_email"
                                            value="{{ old('contact_email', $siteSetting->contact_email ?? '') }}">
                                    </div>
                                </div>

                                <!-- Primary Theme Color -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="theme_color_primary">Primary Theme Color</label>
                                        <input type="color" class="form-control" id="theme_color_primary"
                                            name="theme_color_primary"
                                            value="{{ old('theme_color_primary', $siteSetting->theme_color_primary ?? '#000000') }}">
                                    </div>
                                </div>

                                <!-- Secondary Theme Color -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="theme_color_secondary">Secondary Theme Color</label>
                                        <input type="color" class="form-control" id="theme_color_secondary"
                                            name="theme_color_secondary"
                                            value="{{ old('theme_color_secondary', $siteSetting->theme_color_secondary ?? '#FFFFFF') }}">
                                    </div>
                                </div>

                                <!-- Social Links -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="social_links">Social Links (JSON format)</label>
                                        <input type="text" class="form-control" id="social_links" name="social_links"
                                            value="{{ old('social_links', json_encode($siteSetting->social_links ?? [], JSON_UNESCAPED_SLASHES)) }}">
                                    </div>
                                </div>

                                <!-- App Logo -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="app_logo">App Logo</label>
                                        <input type="file" class="form-control" id="app_logo" name="app_logo">
                                        @if (!empty($siteSetting) && $siteSetting->app_logo)
                                            <img src="{{ asset('app_logo/' . $siteSetting->app_logo) }}" alt="App Logo"
                                                style="width: 100px; margin-top: 10px;">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="pb-5 pt-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($siteSetting) && $siteSetting->id ? 'Update' : 'Create' }}
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endsection

    @section('customJs')
    @endsection
