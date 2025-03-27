@extends('backend.layout.app')

@section('title', 'Tables Management')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tables Management</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Tables</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Manage Tables</h3>
                        <div class="card-tools">
                            <a href="{{ route('tables.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Add New Table
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" id="search" class="form-control form-control-sm" placeholder="Search tables...">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="tablesTable" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Table Number</th>
                                    <th class="text-center">QR Code</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($tables as $table)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $table->table_number }}</td>
                                        <td class="text-center">
                                            <a href="{{ asset($table->qr_code) }}" data-lightbox="{{$loop->iteration}}" data-title="{{$table->table_number}}">
                                                <img src="{{ file_exists(public_path($table->qr_code)) ? asset($table->qr_code) : asset('default/no-image.png') }}"
                                                     alt="QR Code for {{ $table->table_number }}" title="QR Code for {{ $table->table_number }}" style="max-width: 80px; height: 80px;">
                                            </a>
                                            <br>
                                            <button class="btn btn-primary btn-sm download-btn" data-img="{{ asset($table->qr_code) }}" data-name="QR_{{ $table->table_number }}.png">
                                                <i class="fas fa-download"></i> Download
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $table->status == 'active' ? 'badge-success' : 'badge-danger' }}">
                                                {{ ucfirst($table->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-info btn-sm" href="{{ route('tables.edit', $table->id) }}">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                            <a class="btn btn-danger btn-sm" href="#" onclick="confirmDelete({{ $table->id }})">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                            <form id="delete-form-{{ $table->id }}" action="{{ route('tables.destroy', $table->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="pagination-links">
                            {{$tables->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('customJs')
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".download-btn").forEach(button => {
        button.addEventListener("click", function() {
            let imageUrl = this.getAttribute("data-img"); // Get SVG URL
            let imageName = this.getAttribute("data-name").replace('.png', '.svg'); // Ensure .svg extension

            fetch(imageUrl)
                .then(response => response.text()) // Fetch as text for SVG
                .then(svgContent => {
                    let blob = new Blob([svgContent], { type: "image/svg+xml" }); // Create Blob for SVG
                    let link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = imageName; // Download as .svg
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                })
                .catch(error => console.error("Error downloading SVG:", error));
        });
    });
});

</script>
@endsection
