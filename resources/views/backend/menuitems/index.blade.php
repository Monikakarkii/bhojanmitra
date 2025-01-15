@extends('backend.layout.app')

@section('title', 'Menu Items Management')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Menu Items Management</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Menu Items</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Manage Menu Items</h3>
                        <div class="card-tools">
                            <a href="{{ route('menu-items.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Add New Menu Item
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div>
                            <!-- Search Input -->
                            <label>
                                <input type="search" id="search" class="form-control" placeholder="Search menu items...">
                            </label>

                            <!-- Table -->
                            <table class="table table-bordered mt-3" id="menuItemsTable">
                                <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($menuItems as $menuItem)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $menuItem->name }}</td>
                                        <td>${{ number_format($menuItem->price, 2) }}</td>
                                        <td>
                                        
                                            @if($menuItem->availability === 1)
                                                <i class="fas fa-check-circle text-success"></i> Available
                                            @else
                                                <i class="fas fa-times-circle text-danger"></i> Out of Stock
                                            @endif
                                        </td>
                                        <td>
                                            @if($menuItem->image)
                                                <img src="{{ asset($menuItem->image) }}" alt="Menu Item Image"
                                                     style="max-width: 65px; max-height: 65px;">
                                            @else
                                                <span>No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-warning btn-sm"
                                               href="{{ route('menu-items.edit', $menuItem) }}">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete({{ $menuItem->id }})">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                            <form id="delete-form-{{ $menuItem->id }}"
                                                  action="{{ route('menu-items.destroy', $menuItem) }}" method="POST"
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No menu items found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            {{ $menuItems->links() }}
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
        $('#search').on('keyup', function () {
            search();
        });

        function search() {
            var keyword = $('#search').val();

            // Make an AJAX POST request to the search route
            $.post('{{ route("menu-items.search") }}', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                searchTerm: keyword,
            }, function(data) {
                //eror handling
                if (data.error) {
                    alert(data.error);
                    return;
                }
                updateTable(data);
            });
        }

        // Update table with search results
        function updateTable(data) {

            let html = '';
            if (data.menuItems.data.length === 0) {
                html += `<tr><td colspan="6" class="text-center">No menu items found.</td></tr>`;
            } else {
                data.menuItems.data.forEach(function(menuItem, index) {
                    let imageUrl = menuItem.image ? '{{ asset(':image') }}'.replace(':image', menuItem.image) : window.location.origin + '/default/no-image.png';

                    let editUrl = '{{ route("menu-items.edit", ":id") }}'.replace(':id', menuItem.id);
                    let deleteUrl = '{{ route("menu-items.destroy", ":id") }}'.replace(':id', menuItem.id);

                    html += `
                        <tr class="text-center">
                            <td>${index + 1}</td>
                            <td>${menuItem.name}</td>
                            <td>${menuItem.price}</td>
                            <td>
                                ${menuItem.availability === 1 ?
                        `<i class="fas fa-check-circle text-success"></i> Available` :
                        `<i class="fas fa-times-circle text-danger"></i> Out of Stock`}
                            </td>
                            <td>
                                <img src="${imageUrl}" alt="Menu Item Image" style="max-width: 65px; max-height: 65px;" />
                            </td>
                            <td>
                                <a class="btn btn-warning btn-sm" href="${editUrl}">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(${menuItem.id})">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                                <form id="delete-form-${menuItem.id}" action="${deleteUrl}" method="POST" style="display: none;">
                                    @csrf
                    @method('DELETE')
                    </form>
                </td>
            </tr>`;
                });
            }
            $('#menuItemsTable tbody').html(html);
        }
    </script>
@endsection
