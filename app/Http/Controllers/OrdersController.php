<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;


class OrdersController extends Controller
{

    public function checkout()
    {
        $user = auth()->user();
        $products = $user->cart()->withPivot('quantity', 'size')->get();

        if ($products->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Uw winkelwagen is leeg.');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($products as $product) {
            $subtotal += $product->price * $product->pivot->quantity;
        }

        $shipping = 3.90;
        $total = $subtotal + $shipping;

        return view('orders.checkout', compact('products', 'subtotal', 'shipping', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'voornaam' => 'required|string|max:255',
            'achternaam' => 'required|string|max:255',
            'straat' => 'required|string|max:255',
            'huisnummer' => 'required|string|max:20',
            'postcode' => 'required|string|max:10',
            'woonplaats' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $cartItems = $user->cart()->withPivot('quantity', 'size')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Uw winkelwagen is leeg.');
        }

        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'voornaam' => $validated['voornaam'],
                'achternaam' => $validated['achternaam'],
                'straat' => $validated['straat'],
                'huisnummer' => $validated['huisnummer'],
                'postcode' => $validated['postcode'],
                'woonplaats' => $validated['woonplaats'],
            ]);

            // Populate order_product table
            foreach ($cartItems as $item) {
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'quantity' => $item->pivot->quantity,
                    'size' => $item->pivot->size,
                ]);
            }


            // Clear cart
            $user->cart()->detach();

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Bestelling is succesvol geplaatst!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Er is een fout opgetreden bij het plaatsen van de bestelling.');
        }
    }

    // Valideer het formulier zodat alle velden verplicht zijn.
    // Vul het formulier terug in, en toon de foutmeldingen.

    // Maak een nieuw "order" met de gegevens uit het formulier in de databank
    // Zorg ervoor dat hett order gekoppeld is aan de ingelogde gebruiker.

    // Zoek alle producten op die gekoppeld zijn aan de ingelogde gebruiker (shopping cart)
    // Overloop alle gekoppelde producten van een user (shopping cart)
    // Attach het product, met bijhorende quantity en size, aan het order
    // https://laravel.com/docs/9.x/eloquent-relationships#retrieving-intermediate-table-columns
    // Detach tegelijk het product van de ingelogde gebruiker zodat de shopping cart terug leeg wordt

    // BONUS: Als er een discount code in de sessie zit koppel je deze aan het discount_code_id in het order model
    // Verwijder nadien ook de discount code uit de sessie


    // BONUS: Stuur een e-mail naar de gebruiker met de melding dat zijn bestelling gelukt is,
    // samen met een knop of link naar de show pagina van het order


    // Redirect naar de show pagina van het order en pas de functie daar aan


    public function index()
    {
        // Zoek alle orders van de ingelogde gebruiker op. Vervang de "range" hieronder met de juiste code
        $orders = Auth::user()->orders()
            ->with('products')
            ->latest()
            ->get(); // Haal de resultaten op

        // Pas de views aan zodat de juiste info van een order getoond word in de "order" include file
        return view('orders.index', [
            'orders' => $orders
        ]);
    }

    public function show(Order $order)
    { // Order $order
        // Beveilig het order met een GATE zodat je enkel jouw eigen orders kunt bekijken.

        // In de URL wordt het id van een order verstuurd. Zoek het order uit de url op.
        // Zoek de bijbehorende producten van het order hieronder op.
        // if (Gate::denies('view-order', $order)) {
        //     abort(403, 'U heeft geen toegang tot deze bestelling.');
        // }
        $products = $order->products()->withPivot('quantity', 'size')->get();

        // Geef de juiste data door aan de view
        // Pas de "order-item" include file aan zodat de gegevens van het order juist getoond worden in de website
        return view('orders.show', [
            // 'order' => $order,
            'order' => $order,
            'products' => $products
        ]);
    }
}