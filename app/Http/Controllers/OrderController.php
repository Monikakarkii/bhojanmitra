<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment')) {
            $query->where('payment_method', $request->payment);
        }

        // Fetch latest data
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

    public function show($id)
{
    $order = Order::with('items.menuItem')->findOrFail($id);
    return view('backend.orders.show', compact('order'));
}
public function generateBill($id)
{
    $order = Order::with('items.menuItem')->findOrFail($id);

    // Load a PDF view with order data
    $pdf = PDF::loadView('backend.orders.bill', compact('order'));

    // Return the PDF as a downloadable response
    return $pdf->stream('invoice-' . $order->id . '.pdf');
}
public function updatePayment(Request $request, $id)
{
    try {
        // ✅ Validate the incoming request
        $request->validate([
            'pay_status' => 'required|boolean',
            'pay_note' => 'nullable|string|max:255',
        ]);

        // ✅ Find the order (handle if not found)
        $order = Order::findOrFail($id);

        // ✅ Update Payment Status and Notes
        $order->pay_status = $request->pay_status;
        $order->pay_note = $request->pay_note;
        $order->save();

        return back()->with('success', 'Payment status updated successfully!');
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return back()->with('error', 'Order not found.');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->withErrors($e->validator)->withInput();
    } catch (\Exception $e) {
        return back()->with('error', 'Something went wrong! Please try again.');
    }
}


}

