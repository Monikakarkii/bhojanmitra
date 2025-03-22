@extends('backend.layout.app')

@section('title', 'Orders')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <h1><i class="fas fa-receipt"></i> Orders</h1>
        </div>
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('orders.index') }}" method="GET" class="form-inline">
                        <div class="form-group mr-2">
                            <label><i class="fas fa-calendar-alt"></i> Date:</label>
                            <input type="date" name="date" class="form-control ml-2" value="{{ request('date') }}">
                        </div>
                        <div class="form-group mr-2">
                            <label><i class="fas fa-tasks"></i> Status:</label>
                            <select name="status" class="form-control ml-2">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                <option value="ready_to_serve" {{ request('status') == 'ready_to_serve' ? 'selected' : '' }}>Ready to Serve</option>
                                <option value="served" {{ request('status') == 'served' ? 'selected' : '' }}>Served</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label><i class="fas fa-money-bill-wave"></i> Payment:</label>
                            <select name="payment" class="form-control ml-2">
                                <option value="">All Payments</option>
                                <option value="cash" {{ request('payment') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="online" {{ request('payment') == 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                        @if(request('date') || request('status') || request('payment'))
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-redo"></i> Clear
                            </a>
                        @endif
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th><i class="fas fa-chair"></i> Table</th>
                                <th><i class="fas fa-user"></i> Customer</th>
                                <th><i class="fas fa-tasks"></i> Status</th>
                                <th><i class="fas fa-wallet"></i> Payment</th>
                                <th><i class="fas fa-check-circle"></i> Payment Status</th>
                                <th><i class="fas fa-dollar-sign"></i> Total</th>
                                <th><i class="fas fa-clock"></i> Created At</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><i class="fas fa-utensils"></i> Table {{ optional($order->table)->table_number ?? 'N/A' }}</td>
                                    <td class="text-center"><i class="fas fa-id-card"></i> {{ $order->customer_id }}</td>
                                    <td>
                                        <span class="badge badge-{{ $order->order_status == 'canceled' ? 'danger' : ($order->order_status == 'served' ? 'success' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $order->payment_method == 'cash' ? 'info' : 'primary' }}">
                                            <i class="fas fa-money-bill"></i> {{ ucfirst($order->payment_method) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $order->pay_status ? 'success' : 'danger' }}">
                                            <i class="{{ $order->pay_status ? 'fas fa-check-circle' : 'fas fa-times-circle' }}"></i>
                                            {{ $order->pay_status ? 'Paid' : 'Unpaid' }}
                                        </span>
                                    </td>
                                    <td><strong>Rs.{{ number_format($order->total_amount, 2) }}</strong></td>
                                    <td><i class="fas fa-clock"></i> {{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <a href=" {{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        {{-- <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a> --}}
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
                                    <td colspan="9" class="text-center">
                                        <i class="fas fa-exclamation-circle text-danger"></i> No orders found.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
