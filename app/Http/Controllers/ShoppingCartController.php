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

        // Check if the product exists in the cart
        if ($user->cart()->where('product_id', $product->id)->exists()) {
            return redirect()->route('products.index')->with('error', 'Dit product zit al in je winkelwagen!');
        }
        // Add product to cart if it doesn't exist
        $user->cart()->syncWithoutDetaching([
            $product->id => [
                'quantity' => $request->quantity,
                'size' => $request->size,
            ],
        ]);

        return redirect()->route('products.index')->with('success', 'Product toegevoegd aan winkelwagen!');
    }

    public function update(Request $request, Product $product)
    {
        // Validate request data
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'size' => 'required|integer|min:35|max:46',
        ]);

        // Check if the product exists in the cart
        if (!auth()->user()->cart()->where('product_id', $product->id)->exists()) {
            return redirect()->route('products.index')->with('error', 'Product niet gevonden in winkelwagen!');
        }

        // Update the pivot table data
        auth()->user()->cart()->updateExistingPivot($product->id, [
            'quantity' => $request->quantity,
            'size' => $request->size,
        ]);

        return redirect()->route('products.index')->with('success', 'Winkelwagen succesvol bijgewerkt!');
    }


    public function delete(Product $product)
    {
        // Check if the product exists in the cart
        if (!auth()->user()->cart()->where('product_id', $product->id)->exists()) {
            return redirect()->route('cart')->with('error', 'Product niet gevonden in winkelwagen!');
        }

        // Remove the product from the cart
        auth()->user()->cart()->detach($product->id);

        return redirect()->route('cart')->with('success', 'Product verwijderd uit winkelwagen!');
    }


    public function removeDiscountCode()
    {

        session()->forget('discount_code');
        return back();
    }
}
