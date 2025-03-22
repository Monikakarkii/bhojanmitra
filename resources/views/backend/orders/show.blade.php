@extends('backend.layout.app')

@section('title', 'Order Details')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <h1><i class="fas fa-receipt"></i> Order Details - #{{ $order->id }}</h1>
    </div>
    <section class="content">
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Order Information --}}
                <h4 class="mb-3 text-primary"><i class="fas fa-info-circle"></i> Order Information</h4>

                <div class="row">
                    <div class="{{ $order->pay_status == '1' ? 'col-12' : 'col-md-6' }}">
                        <div class="card border-0 shadow-sm p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-chair"></i> Table:</strong> Table {{ $order->table->table_number }}</p>
                                    <p><strong><i class="fas fa-user"></i> Customer ID:</strong> {{ $order->customer_id }}</p>
                                    <p>
                                        <strong><i class="fas fa-money-bill-wave"></i> Payment Status:</strong>
                                        <span class="badge badge-{{ $order->pay_status == 1 ? 'success' : 'danger' }}">
                                            {{ $order->pay_status == 1 ? 'Paid' : 'Unpaid' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <strong><i class="fas fa-clipboard-check"></i> Order Status:</strong>
                                        <span class="badge badge-{{ $order->order_status == 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </p>

                                    <p>
                                        <strong><i class="fas fa-money-bill-wave"></i> Payment Method:</strong>
                                        <span class="badge badge-info">{{ ucfirst($order->payment_method) }}</span>
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-dollar-sign"></i> Total Amount:</strong>
                                        <span class="text-success">Rs.{{ number_format($order->total_amount, 2) }}</span>
                                    </p>
                                    <a href="{{ route('orders.generateBill', $order->id) }}" class="btn btn-success mt-3" target="_blank">
                                        <i class="fas fa-file-invoice"></i> Generate Bill
                                    </a>
                                </div>

                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-calendar-alt"></i> Created At:</strong>
                                        {{ $order->created_at->format('Y-m-d h:i A') }}
                                    </p>

                                </div>
                            </div>


                        </div>
                    </div>

                    {{-- Payment Update Section --}}
                    @if($order->pay_status == '0')
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-3 bg-light">
                            <h5 class="text-danger"><i class="fas fa-money-check"></i> Update Payment Status</h5>
                            <form action="{{ route('orders.updatePayment', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <label for="pay_status"><strong>Payment Status:</strong></label>
                                <select name="pay_status" id="pay_status" class="form-control">
                                    <option value="1">Paid</option>
                                    <option value="0" selected>Unpaid</option>
                                </select>

                                <label for="pay_note" class="mt-2"><strong>Payment Notes:</strong></label>
                                <textarea name="pay_note" id="payment_notes" class="form-control" rows="3" placeholder="Enter payment notes (if any)">{{ $order->payment_notes }}</textarea>

                                <button type="submit" class="btn btn-primary mt-3">
                                    <i class="fas fa-save"></i> Update Payment
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                <hr>

                {{-- Ordered Items --}}
                <h4 class="mt-4 text-primary"><i class="fas fa-utensils"></i> Ordered Items</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hover border">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>#</th>
                                <th><i class="fas fa-image"></i> Image</th>
                                <th><i class="fas fa-hamburger"></i> Item Name</th>
                                <th><i class="fas fa-sort-numeric-up"></i> Quantity</th>
                                <th><i class="fas fa-tag"></i> Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($item->menuItem->image)
                                            <img src="{{ asset($item->menuItem->image) }}"
                                                 alt="{{ $item->menuItem->name }}"
                                                 class="img-thumbnail"
                                                 style="width: 60px; height: 60px;">
                                        @else
                                            <span>No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->menuItem->name ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rs.{{ number_format($item->price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary mt-3">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
