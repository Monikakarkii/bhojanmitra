@extends('kitchen.layout.app') <!-- Assuming you have a layout file named app.blade.php -->

@section('title', 'Order History')

@section('content')
    <div class="content-wrapper">
        <div class="container mt-3">
            <h3 class="text-center mb-3 display-4 text-primary">Order History</h3>

            <!-- Date Filter Form -->
            <form action="{{ route('kitchen.history') }}" method="GET" class="mb-5">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="from_date" class="form-label">From Date:</label>
                        <input type="date" id="from_date" name="from_date" class="form-control"
                            value="{{ $fromDate ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label for="to_date" class="form-label">To Date:</label>
                        <input type="date" id="to_date" name="to_date" class="form-control"
                            value="{{ $toDate ?? '' }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 me-2">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                        <!-- Clear Filter Button -->
                        @if (request('from_date') || request('to_date'))
                            <a href="{{ route('kitchen.history') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-times me-2"></i>Clear Filter
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Orders History -->
            <h2 class="text-center text-secondary mb-3">Orders History</h2>
            <hr>
            <div class="row">
                @if ($orders->isEmpty())
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            No past orders found.
                        </div>
                    </div>
                @else
                    @foreach ($orders as $order)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Order #{{ $order->id }}</h5>
                                    <span
                                        class="badge {{ $order->order_status == 'ready_to_serve' ? 'bg-primary' : 'bg-success' }}">
                                        {{ ucfirst(str_replace('_', ' ', 'Completed')) }}
                                    </span>
                                </div>

                                <div class="card-body">
                                    <p><strong>Table:</strong> {{ $order->table->table_number }}</p>
                                    <p><strong>Items:</strong></p>
                                    <ul class="list-group list-group-flush mb-3">
                                        @foreach ($order->items as $item)
                                            <li class="list-group-item">{{ $item->menuItem->name }}
                                                <span class="badge bg-secondary float-end">Qty: {{ $item->quantity }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <p><strong>Note:</strong> {{ $order->notes }}</p>
                                    <p class="text-muted"><strong>Updated At:</strong>
                                        {{ $order->updated_at->format('d-m-Y H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
