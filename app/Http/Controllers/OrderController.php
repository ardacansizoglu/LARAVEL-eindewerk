<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $user = auth()->user();
        $cartItems = $user->cart;

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Uw winkelwagen is leeg!');
        }

        DB::beginTransaction();

        try {
            // Create new order
            $order = Order::create([
                'user_id' => $user->id,
                'voornaam' => $user->voornaam,
                'achternaam' => $user->achternaam,
                'straat' => $user->straat,
                'huisnummer' => $user->huisnummer,
                'postcode' => $user->postcode,
                'woonplaats' => $user->woonplaats,
            ]);

            // Add cart items to order
            foreach ($cartItems as $item) {
                $order->products()->attach($item->id, [
                    'quantity' => $item->pivot->quantity,
                    'size' => $item->pivot->size,
                ]);
            }

            // Clear the cart
            $user->cart()->detach();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bestelling succesvol geplaatst!',
                'redirect' => route('orders.show', $order)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Er is een fout opgetreden bij het plaatsen van uw bestelling.',
                'redirect' => route('cart')
            ], 500);
        }
    }
}
