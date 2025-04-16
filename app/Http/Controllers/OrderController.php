<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function placeOrder()
    {
        $user = auth()->user();
        $cartItems = $user->cart;

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        // Clear the cart
        $user->cart()->detach();

        return redirect()->route('cart')->with('success', 'Order placed successfully!');
    }
}
