<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('order_status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment') && $request->payment != '') {
            $query->where('payment_method', $request->payment);
        }
        //fetch latest data


        $orders = $query->latest()->paginate(10); // Change pagination as needed

        return view('backend.orders.index', compact('orders'));
    }
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // Store order details before deleting
        $deletedOrder = "Order #{$order->id} (Table: {$order->table_id}, Amount: Rs.{$order->total_amount})";

        // Optional: Prevent deletion of orders that are being prepared or served
        if (in_array($order->order_status, ['preparing', 'ready_to_serve'])) {
            return redirect()->back()->with('error', 'Cannot delete an ongoing order.');
        }

        // Delete related order items first
        $order->items()->delete(); // Ensure orderItems() relation is defined in Order model

        // Now delete the order
        $order->delete();

        return redirect()->route('orders.index')->with('success', "$deletedOrder has been deleted successfully.");
    }



//    public function store(Request $request)
//    {
//        $request->validate([
//            'table_id' => 'required|exists:tables,id',
//            'order_status' => 'required|in:pending,paid,canceled',
//            'payment_method' => 'nullable|in:cash,online',
//            'total_amount' => 'required|numeric|min:0',
//        ]);
//
//        Order::create($request->all());
//
//        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
//    }

}
