<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\Product;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    public function index()
    {
        // Retrieve products from the authenticated user's cart
        $products = auth()->user()->cart()->withPivot('quantity', 'size')->get();

        // Calculate subtotal using pivot table data
        $subtotal = 0;
        foreach ($products as $product) {
            $subtotal += $product->price * $product->pivot->quantity;
        }

        $shipping = 3.9;
        $total = $subtotal + $shipping;

        // Check for discount code in session
        // if (session()->has('discount_code')) {
        //     $discountCode = DiscountCode::where('code', session('discount_code'))->first();
        //     if ($discountCode) {
        //         $discountAmount = $subtotal * ($discountCode->discount_percentage / 100);
        //         $total -= $discountAmount;
        //     } else {
        //         $discountCode = false;
        //         $discountAmount = 0;
        //     }
        // } else {
        //     $discountCode = false;
        //     $discountAmount = 0;
        // }

        return view('cart.index', [
            'products' => $products,
            'shipping' => $shipping,
            'subtotal' => $subtotal,
            'total' => $total,
            // 'discountCode' => $discountCode,
            // 'discountAmount' => $discountAmount,
        ]);
    }

    public function add(Request $request, Product $product)
    {
        // Validate request data
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'size' => 'required|string',
        ]);

        // Attach product to user's cart with pivot data
        $user = auth()->user();
        $user->cart()->syncWithoutDetaching([
            $product->id => [
                'quantity' => $request->quantity,
                'size' => $request->size,
            ],
        ]);

        return redirect()->route('cart')->with('success', 'Product added to cart!');
    }

    public function update(Request $request, Product $product)
    {
        // Validate request data
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'size' => 'required|string',
        ]);

        // Check if the product exists in the cart
        if (!auth()->user()->cart()->where('product_id', $product->id)->exists()) {
            return redirect()->route('cart')->with('error', 'Product not found in cart!');
        }

        // Update the pivot table data
        auth()->user()->cart()->updateExistingPivot($product->id, [
            'quantity' => $request->quantity,
            'size' => $request->size,
        ]);

        return redirect()->route('cart')->with('success', 'Cart updated successfully!');
    }

    // public function setDiscountCode(Request $request)
    // {
    //     // Validate discount code input
    //     $request->validate([
    //         'code' => 'required|string',
    //     ]);

    //     // Check if discount code exists
    //     $discountCode = DiscountCode::where('code', $request->code)->first();
    //     if ($discountCode) {
    //         // Store discount code in session
    //         session(['discount_code' => $discountCode->code]);
    //         return redirect()->route('cart');
    //     }

    //     return back()->withErrors(['code' => 'Discount code not found.']);
    // }

    public function delete(Product $product)
    {
        // Check if the product exists in the cart
        if (!auth()->user()->cart()->where('product_id', $product->id)->exists()) {
            return redirect()->route('cart')->with('error', 'Product not found in cart!');
        }

        // Remove the product from the cart
        auth()->user()->cart()->detach($product->id);

        return redirect()->route('cart')->with('success', 'Product removed from cart!');
    }


    public function removeDiscountCode()
    {
        // Remove discount code from session
        session()->forget('discount_code');
        return back();
    }
}
