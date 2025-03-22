<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Order #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .total { font-weight: bold; }
        .text-center { text-align: center; }
        .company-logo { text-align: center; margin-bottom: 10px; }
        .footer { margin-top: 40px; }
        .signature { float: right; text-align: center; margin-top: 50px; }
    </style>
</head>
<body>


    <h2 class="text-center"> {{ websiteInfo() ? websiteInfo()->app_name : 'Default App Name' }}</h2>
    <p><strong>Order ID:</strong> #{{ $order->id }}</p>
    <p><strong>Table:</strong> {{ $order->table->table_number }}</p>
    <p><strong>Customer ID:</strong> {{ $order->customer_id }}</p>
    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
    <p ><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d h:i A') }}</p>
    <p><strong>Bill Generated On:</strong> {{ now()->format('Y-m-d h:i A') }}</p> <!-- Bill Generation Date -->
    <p><strong>Payment Status:</strong>
        @if($order->pay_status == '1')
            <span style="color: green; font-weight: bold;">Paid</span>
        @else
            <span style="color: red; font-weight: bold;">Unpaid</span>
        @endif
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->menuItem->name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rs.{{ number_format($item->price, 2) }}</td>
                    <td>Rs.{{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="total">Total</td>
                <td class="total">Rs.{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <p class="text-center">Thank you for dining with us!</p>

    <!-- Signature Section -->
    <div class="footer">
        <div class="signature">
            <p>________________________</p>
            <p><strong>Authorized Signature</strong></p>
        </div>
    </div>

</body>
</html>
