<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function placeOrder(Product $product)
    {
        $user = auth()->user();

        // Logic to place an order (e.g., create an order record in the database)
        $user->orders()->create([
            'product_id' => $product->id,
            'quantity' => 1, // Default quantity
            'status' => 'pending',
        ]);

        return redirect()->route('cart')->with('success', 'Order placed successfully!');
    }
}
