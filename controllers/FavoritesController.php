<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class FavoritesController extends Controller
{
    public function favorites()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to view favorites.');
        }

        $favorites = $user->favorites;
        return view('profile.favorites', ['products' => $favorites]);
    }

    public function toggleFavorite(Product $product)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to toggle favorites.');
        }

        $user->favorites()->toggle($product->id);
        return back();
    }

    public function toggleFavorites(Request $request, Product $product)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'You must be logged in to toggle favorites.'], 401);
        }

        if ($user->favorites()->where('product_id', $product->id)->exists()) {
            $user->favorites()->detach($product->id);
        } else {
            $user->favorites()->attach($product->id);
        }

        return response()->json(['success' => true]);
    }
}
