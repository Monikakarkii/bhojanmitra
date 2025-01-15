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
                                <thead >
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
                                        <td class="text-center">
                                            <a href="{{ route('tables.edit', $table->id) }}">
                                                {{ $loop->iteration }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('tables.edit', $table->id) }}">
                                                {{ $table->table_number }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('tables.edit', $table->id) }}">
                                                <img src="{{ file_exists(public_path($table->qr_code)) ? asset($table->qr_code) : asset('default/no-image.png') }}"
                                                     alt="QR Code for {{ $table->table_number }}" title="QR Code for {{ $table->table_number }}" style="max-width: 80px; height: 80px;">
                                            </a>
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
        $('#search').on('keyup', function() {
            search();
        });

        // Listen for changes in the "perPage" dropdown to change pagination dynamically
        $('#perPage').on('change', function() {
            search();
        });

        function search() {
            var keyword = $('#search').val();
            var perPage = $('#perPage').val(); // Get the selected number of items per page

            $.post('{{ route("tables.search") }}', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                keyword: keyword,
                per_page: perPage  // Send per_page as part of the request
            }, function(data) {
                table_post_row(data);
                updatePagination(data);  // Update the pagination links dynamically
            });
        }

        function table_post_row(res) {
            let htmlView = '';
            if (res.tables.data.length <= 0) {
                htmlView += `
                <tr>
                    <td colspan="5" class="text-center">No data found.</td>
                </tr>`;
            }
            for (let i = 0; i < res.tables.data.length; i++) {
                let qrCodeUrl = window.location.origin + '/' + res.tables.data[i].qr_code;
                qrCodeUrl = (new Image().src = qrCodeUrl, new Image().complete) ? qrCodeUrl : window.location.origin + '/default/no-image.png';
                let editUrl = '{{ route("tables.edit", ":id") }}'.replace(':id', res.tables.data[i].id); // Dynamic edit route
                let deleteUrl = '{{ route("tables.destroy", ":id") }}'.replace(':id', res.tables.data[i].id); // Dynamic delete route

                htmlView += `
                <tr>
                    <td class="text-center">${i + 1}</td>
                    <td class="text-center">${res.tables.data[i].table_number}</td>
                    <td class="text-center">
                          <img src="${qrCodeUrl}" alt="QR Code for ${res.tables.data[i].table_number}" title="QR Code for ${res.tables.data[i].table_number}" style="max-width: 80px; height: 80px;">
                    </td>
                    <td class="text-center">
                        <span class="badge ${res.tables.data[i].status === 'available' ? 'badge-success' : 'badge-danger'}">
                            ${res.tables.data[i].status.charAt(0).toUpperCase() + res.tables.data[i].status.slice(1)}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center">
                            <a href="${editUrl}" class="btn btn-info btn-sm mr-2">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </a>
                            <a href="#" onclick="confirmDelete(${res.tables.data[i].id})" class="btn btn-danger btn-sm mr-2">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                            <form id="delete-form-${res.tables.data[i].id}" action="${deleteUrl}" method="POST" style="display: none;">
                                @csrf
                @method('DELETE')
                </form>
            </div>
        </td>
    </tr>`;
            }
            $('tbody').html(htmlView);
        }
    </script>
@endsection
