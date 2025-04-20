<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\Product;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{

    public function index()
    {
        // Get cart products
        $products = auth()->user()->cart()->withPivot('quantity', 'size')->get();

        // Calculate subtotal
        $subtotal = $products->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });

        $shipping = 3.9;
        $discount = 0;
        $discountCode = null;

        // Check for discount code in session
        if (session()->has('discount_code')) {
            $discountCode = DiscountCode::where('code', session('discount_code'))
                ->where('is_active', true)
                ->first();

            if ($discountCode) {
                $discount = $discountCode->discount_type === 'percentage'
                    ? ($subtotal * $discountCode->discount_amount / 100)
                    : $discountCode->discount_amount;
            }
        }

        $total = $subtotal + $shipping - $discount;

        return view('cart.index', [
            'products' => $products,
            'shipping' => $shipping,
            'subtotal' => $subtotal,
            'total' => $total,
            'discountCode' => $discountCode,
            'discount' => $discount
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

    public function setDiscountCode(Request $request)
    {
        // Validate the discount code
        $request->validate([
            'discount_code' => 'required|string|max:255',
        ]);

        // Check if the discount code exists and is valid
        $discountCode = DiscountCode::where('code', $request->discount_code)->first();

        if ($discountCode && $discountCode->isValid()) {
            session(['discount_code' => $request->discount_code]);
            return back()->with('success', 'Kortingscode succesvol toegepast!');
        } else {
            return back()->with('error', 'Ongeldige kortingscode!');
        }
    }

    public function applyDiscount(Request $request)
    {
        $code = $request->input('code');

        // Find valid discount code
        $discountCode = DiscountCode::where('code', $code)
            ->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where(function ($query) {
                $query->where('valid_until', '>=', now())
                    ->orWhereNull('valid_until');
            })
            ->first();

        if (!$discountCode) {
            return response()->json([
                'success' => false,
                'message' => 'Ongeldige kortingscode.'
            ]);
        }

        // Calculate discount
        $subtotal = auth()->user()->cart->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });

        $discount = 0;
        if ($discountCode->discount_type === 'percentage') {
            $discount = $subtotal * ($discountCode->discount_amount / 100);
        } else {
            $discount = $discountCode->discount_amount;
        }

        // Store discount code in session
        session(['discount_code' => $code]);

        return response()->json([
            'success' => true,
            'message' => 'Kortingscode toegepast!',
            'discount' => $discount,
            'new_total' => $subtotal + 3.9 - $discount // 3.9 is shipping cost
        ]);
    }
}