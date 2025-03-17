@extends('backend.layout.app')

@section('title', 'Orders')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <h1>Orders</h1>
        </div>
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('orders.index') }}" method="GET" class="form-inline">
                        <div class="form-group mr-2">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="form-group mr-2">
                            <select name="status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                <option value="ready_to_serve" {{ request('status') == 'ready_to_serve' ? 'selected' : '' }}>Ready to Serve</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select name="payment" class="form-control">
                                <option value="">All Payments</option>
                                <option value="cash" {{ request('payment') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="online" {{ request('payment') == 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        @if(request('date') || request('status') || request('payment'))
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-redo"></i> Clear Filter
                            </a>
                        @endif
                    </form>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Table</th>
                            <th>Customer_id</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Total</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>Table {{ $order->table_id }}</td>
                                <td class="text-center">{{ $order->customer_id }}</td>
                                <td>
                                    <span class="badge badge-{{ $order->order_status == 'paid' ? 'success' : ($order->order_status == 'canceled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td>{{ ucfirst($order->payment_method) }}</td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td>{{ $order->created_at->format('Y-m-d h:i A') }}</td>

                                <td>
{{--                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">View</a>--}}
                                    <a href="#" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <a class="btn btn-danger btn-sm" href="#" onclick="confirmDelete({{ $order->id }})">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                    <form id="delete-form-{{ $order->id }}" action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No orders found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
