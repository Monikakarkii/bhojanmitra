<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class KitchenController extends Controller
{
    public function dashboard()
    {
        // Fetch orders with 'pending' or 'preparing' status
        $orders = Order::whereIn('order_status', ['pending', 'preparing'])->latest()->get();

        // Pass orders to the view
        return view('kitchen.dashboard', compact('orders'));
    }
public function changeStatus($id, $status)
{
    $order = Order::findOrFail($id);
    $order->update([
        'order_status' => $status,
        'updated_at' => now()  // Update the updated_at timestamp
    ]);
    return redirect()->back()->with('success', 'Order status updated successfully.');
}
public function history(Request $request)
{
    // Fetch date range from the request
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');

    // Query orders based on date range and status
    $ordersQuery = Order::whereIn('order_status', ['ready_to_serve', 'paid']);

    if ($fromDate && $toDate) {
        $ordersQuery->whereBetween('created_at', [$fromDate, $toDate]);
    }

    $orders = $ordersQuery->with('items')->latest()->paginate(10);

    // Pass orders and filter values to the view
    return view('kitchen.history', compact('orders', 'fromDate', 'toDate'));
}


}
