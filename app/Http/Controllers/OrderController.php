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
