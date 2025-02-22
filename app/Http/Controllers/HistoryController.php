<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        // Assuming you have the customer token stored in the session
        $customerToken = $request->session()->get('user_token');

        // Find or create the customer using the token
        $customer = UserToken::where('token', $customerToken)->first();

        if (!$customer) {
            return redirect()->route('home')->with('error', 'Customer not found.');
        }

        // Get orders associated with the customer
        $orders = Order::where('customer_id', $customer->id)
        ->with('items')
        ->latest()
        ->paginate(6);

        return view('frontend.history', compact('orders'));
    }
}
