<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sale;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Neputer\Facades\Khalti;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class FrontendOrderController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        if (!in_array($request->paymentMethod, ['cash', 'online'])) {
            return redirect()->back()->with('error', 'Invalid payment method.');
        }

        if ($request->paymentMethod === "cash") {
            $order = $this->saveOrder($request);
            return redirect()->route('menu.order.history')->with('success', 'Order placed successfully!');
        }

        if ($request->paymentMethod === "online") {
            return $this->validateOnlinePayment($request);
        }
    }

    private function saveOrder($request)
    {



        try {
            $table_id = session('user_table');
            $cart = session()->get('cart', []);

            if (empty($cart)) {
                \Log::error('Cart is empty during order saving.');
                return null;
            }

            $grandTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
            $order = new Order();
            $order->table_id = $table_id;
            $order->order_status = 'pending';
            $order->payment_method = $request->paymentMethod ?? 'online';
            $order->total_amount = $grandTotal;
            $order->notes = $request->input('cart_note');
            $order->pay_status = ($order->payment_method === 'online') ? 1 : 0;

            $customerToken = session('user_token');
            if ($customerToken) {
                $customer = UserToken::firstOrCreate(['token' => $customerToken]);
                $order->customer_id = $customer->id;
            }

            $order->created_at = now();
            $order->save();

            foreach ($cart as $menu_item_id => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menu_item_id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            Sale::create([
                'order_id' => $order->id,
                'total_amount' => $grandTotal,
                'payment_method' => $request->paymentMethod ?? 'online',
                'completed_at' => now(),
            ]);

            session()->forget('cart');
            return $order;
        } catch (\Exception $e) {
            \Log::error('Error saving order: ' . $e->getMessage());
            return null;
        }
    }

    private function validateOnlinePayment(Request $request)
    {
        $cart = session()->get('cart', []);
        $grandTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        $return_url = config('app.url') . '/callback';
        $purchase_order_id = uniqid(); // Generate a unique ID for the transaction
        $purchase_order_name = "Order for Table " . session('user_table'); // Dynamic order name
        $amount = $grandTotal * 100; // Convert total amount to paisa

        $response = Khalti::initiate($return_url, $purchase_order_id, $purchase_order_name, $amount);

        if (!$response || !isset($response->payment_url)) {
            \Log::error('Failed to initiate Khalti payment.');
            return redirect()->back()->with('error', 'Failed to initiate payment.');
        }

        return Redirect::to($response->payment_url);
    }

    public function verify(Request $request)
    {
        // Log the incoming request for debugging
        \Log::info('Khalti Callback Request:', $request->all());

        // Extract payment details from the request
        $pidx = $request->query('pidx');
        $status = $request->query('status');
        $transactionId = $request->query('transaction_id');
        $amount = $request->query('amount');
        $purchaseOrderId = $request->query('purchase_order_id');

        // Check if the payment was successful
        if ($status === 'Completed') {
            // Handle successful payment
            \Log::info('Payment successful:', [
                'pidx' => $pidx,
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'purchase_order_id' => $purchaseOrderId,
            ]);

            // Save the order and clear the cart
            $order = $this->saveOrder($request);

            if ($order) {
                $order->update(['pay_status' => 1]);
                // Clear the cart from the session
                session()->forget('cart');

                // Redirect the user to a success page
                return redirect()->route('menu.order.history')->with('success', 'Payment successful and Order placed successfully!');
            } else {
                // Handle order saving failure
                \Log::error('Failed to save order after successful payment.');
                return redirect()->route('menu.cart.view')->with('error', 'Failed to save order.');
            }
        } else {
            // Handle failed or pending payment
            \Log::error('Payment failed or pending:', [
                'pidx' => $pidx,
                'status' => $status,
            ]);

            // Redirect the user to a failure page
            return redirect()->route('menu.cart.view')->with('error', 'Payment failed.');
        }
    }
}
