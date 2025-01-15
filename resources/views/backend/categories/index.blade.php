@extends('backend.layout.app')

@section('title', 'Categories Management')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Categories Management</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Categories</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Manage Categories</h3>
                        <div class="card-tools">
                            <a href="{{ route('categories.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Add New Category
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div>
                            <!-- Search Input -->
                            <label>
                                <input type="search" id="search" class="form-control"
                                       placeholder="Search categories...">
                            </label>

                            <!-- Table -->
                            <table class="table table-bordered mt-3" id="categoriesTable">
                                <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Logo & Icon</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Show on Nav</th>
                                    <th>Nav Index</th>
                                    <th>Show on Home</th>
                                    <th>Home Index</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($categories as $category)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($category->logo)
                                                <img src="{{ asset($category->logo) }}" alt="Logo" style="max-width: 45px; max-height: 45px;">
                                            @else
                                                <span>No Logo</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            @if($category->status === 'active')
                                                <i class="fas fa-check-circle text-success"></i> {{ ucfirst($category->status) }}
                                            @elseif($category->status === 'inactive')
                                                <i class="fas fa-times-circle text-danger"></i> {{ ucfirst($category->status) }}
                                            @else
                                                <span>No Status</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($category->show_on_nav)
                                                <i class="fas fa-check-circle text-success"></i> Yes
                                            @else
                                                <i class="fas fa-times-circle text-danger"></i> No
                                            @endif
                                        </td>
                                        <td>{{ $category->nav_index }}</td>
                                        <td>
                                            @if($category->show_on_home)
                                                <i class="fas fa-check-circle text-success"></i> Yes
                                            @else
                                                <i class="fas fa-times-circle text-danger"></i> No
                                            @endif
                                        </td>
                                        <td>{{ $category->home_index }}</td>
                                        <td>
                                            <a class="btn btn-warning btn-sm" href="{{ route('categories.edit', $category) }}">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $category->id }})">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                            <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No categories found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('customJs')
    <script>
        // Handle search input keyup event
        $('#search').on('keyup', function() {
            search();
        });

        function search() {
            var keyword = $('#search').val();

            // Make an AJAX POST request to the search route
            $.post('{{ route("categories.search") }}', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                searchTerm: keyword,
            }, function(data) {
                updateTable(data);
            });
        }

        // Update table with search results
        function updateTable(data) {
            let html = '';
            if (data.categories.data.length === 0) {
                html += `<tr><td colspan="9" class="text-center">No categories found.</td></tr>`;
            } else {
                data.categories.data.forEach(function(category, index) {
                    // Fallback for logo
                    let logoUrl = category.logo ? '{{ asset(':logo') }}'.replace(':logo', category.logo) : window.location.origin + '/default/no-image.png';

                    // Dynamic edit and delete URLs
                    let editUrl = '{{ route("categories.edit", ":id") }}'.replace(':id', category.id);
                    let deleteUrl = '{{ route("categories.destroy", ":id") }}'.replace(':id', category.id);

                    html += `
                <tr class="text-center">
                    <td>${index + 1}</td>
                    <td>
                        <img src="${logoUrl}" alt="Logo" style="max-width: 45px; max-height: 45px;" />
                    </td>
                    <td>${category.name}</td>
                    <td>
                        ${category.status === 'active' ?
                        `<i class="fas fa-check-circle text-success"></i> Active` :
                        `<i class="fas fa-times-circle text-danger"></i> Inactive`}
                    </td>
                    <td>
                        ${category.show_on_nav === 1 ?
                        `<i class="fas fa-check-circle text-success"></i> Yes` :
                        `<i class="fas fa-times-circle text-danger"></i> No`}
                    </td>
                    <td>${category.nav_index}</td>
                    <td>
                        ${category.show_on_home === 1 ?
                        `<i class="fas fa-check-circle text-success"></i> Yes` :
                        `<i class="fas fa-times-circle text-danger"></i> No`}
                    </td>
                    <td>${category.home_index}</td>
                    <td>
                        <a class="btn btn-warning btn-sm" href="${editUrl}">
                            <i class="fas fa-pencil-alt"></i> Edit
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(${category.id})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        <form id="delete-form-${category.id}" action="${deleteUrl}" method="POST" style="display: none;">
                            @csrf
                    @method('DELETE')
                    </form>
                </td>
            </tr>
`;
                });
            }
            $('#categoriesTable tbody').html(html);
        }
    </script>
@endsection
