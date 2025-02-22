<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);

        $id = $request->id;

        if (isset($cart[$id])) {
            // Update quantity if item already in cart
            $cart[$id]['quantity'] += $request->quantity;
        } else {
            // Add new item to cart
            $cart[$id] = [
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'image' => $request->image
            ];
        }

        session()->put('cart', $cart);

        return response()->json(['cartCount' => count($cart)]);
    }

    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true, 'cartCount' => count($cart)]);
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true, 'cartCount' => count($cart)]);
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('frontend.cart.index', compact('cart'));
    }
}

