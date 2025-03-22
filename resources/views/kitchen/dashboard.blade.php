@extends('kitchen.layout.app') <!-- Assuming you have a layout file named app.blade.php -->

@section('title', 'Dashboard') <!-- Page Title -->

@section('content')
    <div class="content-wrapper">
        <div class="container mt-4">
            <h1 class="text-center mb-4 text-primary">Kitchen Dashboard</h1>

            <!-- Pending Orders -->
            <div class="mb-5">
                <h2 class="text-center text-secondary">Pending Orders</h2>
                <hr class="mb-4">
                <div class="row">
                    @if ($orders->where('order_status', 'pending')->isEmpty())
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> No pending orders at the moment.
                            </div>
                        </div>
                    @else
                        @foreach ($orders->where('order_status', 'pending') as $order)
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm h-100">
                                    <div
                                        class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                                        <strong>Order #{{ $order->id }}</strong>
                                        <span><i class="fas fa-clock"></i> {{ $order->created_at->format('h:i A') }}</span>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Table:</strong> {{ $order->table->table_number }}</p>
                                        <p><strong>Items:</strong></p>
                                        <ul class="list-unstyled">
                                            @foreach ($order->items as $item)
                                                <li><i class="fas fa-utensils"></i> {{ $item->menuItem->name }} (Qty:
                                                    {{ $item->quantity }})</li>
                                            @endforeach
                                        </ul>
                                        <p><strong>Note:</strong> {{ $order->notes ?? 'No special instructions.' }}</p>
                                        <form method="POST"
                                            action="{{ route('order.changeStatus', [$order->id, 'preparing']) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="text-right">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check-circle"></i> Accept
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Preparing Orders -->
            <div class="mb-5">
                <h2 class="text-center text-warning">Preparing Orders</h2>
                <hr class="mb-4">
                <div class="row">
                    @if ($orders->where('order_status', 'preparing')->isEmpty())
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> No orders are currently being prepared.
                            </div>
                        </div>
                    @else
                        @foreach ($orders->where('order_status', 'preparing') as $order)
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm h-100">
                                    <div
                                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <strong>Order #{{ $order->id }}</strong>
                                        <span><i class="fas fa-clock"></i> {{ $order->created_at->format('h:i A') }}</span>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Table:</strong> {{ $order->table->table_number }}</p>
                                        <p><strong>Items:</strong></p>
                                        <ul class="list-unstyled">
                                            @foreach ($order->items as $item)
                                                <li><i class="fas fa-utensils"></i> {{ $item->menuItem->name }} (Qty:
                                                    {{ $item->quantity }})</li>
                                            @endforeach
                                        </ul>
                                        <p><strong>Note:</strong> {{ $order->notes ?? 'No special instructions.' }}</p>
                                        <form method="POST"
                                            action="{{ route('order.changeStatus', [$order->id, 'ready_to_serve']) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-check-circle"></i> Prepared
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
