@extends('backend.layout.app') <!-- Assuming you have a layout file named app.blade.php -->

@section('title', 'Edit Table')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Table</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('tables.index') }}">Tables</a></li>
                            <li class="breadcrumb-item active">Edit Table</li>
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
                        <h3 class="card-title">Edit Table Details</h3>
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

                        <!-- Table Edit Form -->
                        <form action="{{ route('tables.update', $table->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Table Number Field -->
                            <div class="form-group">
                                <label for="table_number">Table Number</label>
                                <input type="text" class="form-control" id="table_number" name="table_number"
                                    value="{{ old('table_number', $table->table_number) }}" required>
                            </div>

                            <!-- QR Code Upload Field -->
                            <div class="form-group">
                                <label for="qr_code">QR Code</label>
                                @if ($table->qr_code)
                                    <div class="mt-2">
                                        <label>Current QR Code:</label>
                                        <br>
                                        <img src="{{ asset($table->qr_code) }}" alt="Current QR Code"
                                            style="max-width: 150px; height: auto;">
                                    </div>
                                @endif
                            </div>

                          <!-- Status Field -->
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active" {{ old('status', $table->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $table->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>


                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Update Table</button>
                            <a href="{{ route('tables.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
