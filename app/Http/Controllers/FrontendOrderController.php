<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sale;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Zerkxubas\EsewaLaravel\Facades\Esewa;
use Dipesh79\LaravelKhalti\LaravelKhalti;

class FrontendOrderController extends Controller
{
    public function store(Request $request)
{
    // Retrieve cart from session
    $cart = session()->get('cart', []);

    // Check if cart is empty
    if (empty($cart)) {
        return redirect()->back()->with('error', 'Your cart is empty.');
    }

    // Handle Cash Payment
    if ($request->paymentMethod == "cash") {
        // Save the order in the database
        $order = $this->saveOrder($request);


        return redirect()->route('menu.order.history')->with('success', 'Order placed successfully!');


    }

    // Handle Online Payment (eSewa/Khalti)
    if ($request->paymentMethod == "online") {
        return $this->validateOnlinePayment($request);
    }

    return redirect()->back()->with('error', 'Invalid payment method.');
}


    /**
     * Save Order and Related Data
     */
   private function saveOrder($request)
{
    $table_id = session('user_table'); // Assuming table_id is stored in session
    $cart = session()->get('cart', []); // Get cart from session
    $grandTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

    // Create Order
    $order = new Order();
    $order->table_id = $table_id;
    $order->order_status = 'pending'; // Set as pending until confirmed
    $order->payment_method = $request->paymentMethod;
    $order->total_amount = $grandTotal;
    $order->notes = $request->note ?? null;

    // Retrieve or Create Customer
    $customerToken = $request->session()->get('user_token');
    $customer = UserToken::firstOrCreate(['token' => $customerToken]);
    $order->customer_id = $customer->id;
    $order->created_at = now()->setTimezone('Asia/Kathmandu');
    $order->save();

    // Save Order Items
    foreach ($cart as $menu_item_id => $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'menu_item_id' => $menu_item_id, // Use the array key as the menu item ID
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }

    // Record Sale
    Sale::create([
        'order_id' => $order->id,
        'total_amount' => $grandTotal,
        'payment_method' => $request->paymentMethod,
        'completed_at' => now()->setTimezone('Asia/Kathmandu'),
    ]);

    // Clear Cart Session
    session()->forget('cart');

    return $order;
}


    /**
     * Validate online payment using eSewa API.
     */
    private function validateOnlinePayment(Request $request)
{
    $khalti = new LaravelKhalti();
    $amount = 123; // Convert to paisa (e.g., Rs 1 = 100 paisa)
    $order_id = time(); // Unique Order ID
    $order_name = "Food Order Payment";

    // Initiate Khalti Payment
    $payment_response = $khalti->khaltiCheckout($amount, $order_id, $order_name);

    if ($payment_response['success']) {
        return redirect($payment_response['url']); // Redirect to payment page
    } else {
        return redirect()->back()->with('error', 'Payment processing failed.');
    }
}


}
