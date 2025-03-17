@extends('backend.layout.app')
@section('title', 'Sales')

@section('content')
    <div class="content-wrapper p-3">
        <div class="card shadow-sm">
            <div class="card-header">
                <h1 class="mb-0">Sales Records</h1>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <div class="mb-4">
                    <form action="{{ route('sales.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-md-6 col-lg-3">
                                <label for="payment_method" class="form-label fw-bold">Payment Method</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                    <select name="payment_method" id="payment_method" class="form-select">
                                        <option value="">Select Payment Method</option>
                                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="online" {{ request('payment_method') == 'online' ? 'selected' : '' }}>Online</option>
                                    </select>
                                </div>
                            </div>


                            <!-- Start Date -->
                            <div class="col-12 col-md-6 col-lg-2 d-flex flex-column">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control flex-grow-1">
                            </div>

                            <!-- End Date -->
                            <div class="col-12 col-md-6 col-lg-2 d-flex flex-column">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control flex-grow-1">
                            </div>

                            <!-- Filter and Clear Buttons -->
                            <div class="col-12 col-md-6 col-lg-4 d-flex align-items-end ml-1">
                                <button type="submit" class="btn btn-primary flex-grow-1 mr-2">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                @if (request('payment_method') || request('date_range') || request('start_date') || request('end_date'))
                                    <a href="{{ route('sales.index') }}" class="btn btn-secondary flex-grow-1">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Download Button -->
                <div class="mb-4">
                    <a href="{{ route('sales.download', ['payment_method' => request('payment_method'), 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                        class="btn btn-success">
                        <i class="fas fa-download"></i> Download Records
                    </a>
                </div>

                <!-- Sales Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Total Amount</th>
                                <th>Payment Method</th>
                                <th>Completed At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sale->order_id }}</td>
                                    <td>{{ $sale->total_amount }}</td>
                                    <td>{{ ucfirst($sale->payment_method) }}</td>
                                    <td>
                                        <i class="fas fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($sale->completed_at)->format('Y-m-d h:i A') }}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No sales records found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
