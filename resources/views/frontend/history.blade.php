@extends('frontend.layout.app')

@section('title', 'Order History')

@section('content')
    <div class="container mt-5">
        <div class="mb-3 d-flex align-items-center">
            <a href="{{ url()->previous() }}" style="color: var(--primary-color); text-decoration: none;">
                <i class="fas fa-arrow-left" style="font-size: 1.4rem;"></i>
            </a>
            <h3 style="color: var(--primary-color); margin: 0 auto;">Your Order History</h3>
        </div>

        @if ($orders->isEmpty())
            <p style="color: var(--text-color);">No past orders found.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover mt-4">
                    <thead>
                        <tr>
                            <th style="color: var(--text-color);">Order ID</th>
                            <th style="color: var(--text-color);">Status</th>
                            <th style="color: var(--text-color);">Items</th>
                            <th style="color: var(--text-color);">Total Amount</th>
                            <th style="color: var(--text-color);">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td style="color: var(--text-color);">{{ $order->id }}</td>
                                <td style="color: var(--text-color);">
                                    <span
                                        class="badge
                                        @if ($order->order_status == 'pending') bg-warning
                                        @elseif($order->order_status == 'completed') bg-success
                                        @elseif($order->order_status == 'canceled') bg-danger @endif">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td style="color: var(--text-color);">
                                    @foreach ($order->items as $item)
                                        <p>{{ $item->menuItem->name }} x {{ $item->quantity }}</p>
                                    @endforeach
                                </td>
                                <td style="color: var(--text-color);">Rs {{ number_format($order->total_amount, 2) }}</td>
                                <td style="color: var(--text-color);">
                                    <i class="fas fa-calendar-alt"></i> {{ $order->created_at->format('d M, Y') }}
                                    <br>
                                    <i class="fas fa-clock"></i> {{ $order->created_at->format('h:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
             <!-- Pagination Links -->
             <div class="d-flex justify-content-center mt-4">
                <style>
                    .pagination .page-link {
                        color: var(--primary-color) !important;
                        background-color: var(--background-color) !important;
                        border: 1px solid var(--primary-color) !important;
                        transition: all 0.3s ease-in-out;
                    }

                    .pagination .page-link:hover {
                        background-color: var(--primary-color) !important;
                        color: var(--background-color) !important;
                    }

                    .pagination .page-item.active .page-link {
                        background-color: var(--primary-color) !important;
                        border-color: var(--primary-color) !important;
                        color: var(--background-color) !important;
                    }

                    .pagination .page-item.disabled .page-link {
                        color: #ccc !important;
                        background-color: var(--background-color) !important;
                        border: 1px solid #ddd !important;
                        cursor: not-allowed;
                    }
                </style>
                {{ $orders->links() }}
            </div>


        @endif
    </div>
@endsection
