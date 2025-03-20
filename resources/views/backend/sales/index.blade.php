@extends('backend.layout.app')

@section('title', 'Sales')

@section('content')
    <div class="content-wrapper p-3">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1 class="mb-0"><i class="fas fa-chart-line"></i> Sales Records</h1>
                <a href="{{ route('sales.download', ['payment_method' => request('payment_method'), 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                   class="btn btn-success">
                    <i class="fas fa-file-download"></i> Download Records
                </a>
            </div>

            <div class="card-body">
                <!-- Filter Form -->
                <div class="mb-4">
                    <form action="{{ route('sales.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <!-- Payment Method -->
                            <div class="col-12 col-md-6 col-lg-3">
                                <label for="payment_method" class="form-label fw-bold"><i class="fas fa-wallet"></i> Payment Method</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                    <select name="payment_method" id="payment_method" class="form-select">
                                        <option value="">All Methods</option>
                                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="online" {{ request('payment_method') == 'online' ? 'selected' : '' }}>Online</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Start Date -->
                            <div class="col-12 col-md-6 col-lg-2">
                                <label for="start_date" class="form-label fw-bold"><i class="fas fa-calendar-alt"></i> Start Date</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                            </div>

                            <!-- End Date -->
                            <div class="col-12 col-md-6 col-lg-2">
                                <label for="end_date" class="form-label fw-bold"><i class="fas fa-calendar-check"></i> End Date</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                            </div>

                            <!-- Filter and Clear Buttons -->
                            <div class="col-12 col-md-6 col-lg-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary flex-grow-1 me-2">
                                    <i class="fas fa-filter"></i> Apply Filter
                                </button>
                                @if (request('payment_method') || request('start_date') || request('end_date'))
                                    <a href="{{ route('sales.index') }}" class="btn btn-secondary flex-grow-1">
                                        <i class="fas fa-times-circle"></i> Clear Filter
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Sales Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark text-light">
                        <tr>
                            <th>#</th>
                            <th><i class="fas fa-receipt"></i> Order ID</th>
                            <th><i class="fas fa-dollar-sign"></i> Total Amount</th>
                            <th><i class="fas fa-wallet"></i> Payment Method</th>
                            <th><i class="fas fa-clock"></i> Completed At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><i class="fas fa-hashtag"></i> {{ $sale->order_id }}</td>
                                <td><strong>Rs. {{ number_format($sale->total_amount, 2) }}</strong></td>
                                <td>
                                    <span class="badge badge-{{ $sale->payment_method == 'cash' ? 'info' : 'primary' }}">
                                        <i class="fas fa-money-bill"></i> {{ ucfirst($sale->payment_method) }}
                                    </span>
                                </td>
                                <td>
                                    <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($sale->completed_at)->format('Y-m-d h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="fas fa-exclamation-circle text-danger"></i> No sales records found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
