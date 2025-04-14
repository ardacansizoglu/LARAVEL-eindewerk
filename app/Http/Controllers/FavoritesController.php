<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{
    public function favorites()
    {
        $favorites = Auth::user()->favorites;
        return view('profile.favorites', ['favorites' => $favorites]);
    }

    public function toggleFavorite(Product $product)
    {
        $user = auth()->user();

        // Check if the product is already a favorite
        if ($user->favorites()->where('product_id', $product->id)->exists()) {
            // Remove from favorites
            $user->favorites()->detach($product->id);
        } else {
            // Add to favorites
            $user->favorites()->attach($product->id);
        }

        return back()->with('success', 'Favorite status updated!');
    }
}
