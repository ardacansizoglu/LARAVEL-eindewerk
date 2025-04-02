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
        return view('profile.favorites', ['products' => $favorites]);
    }

    public function toggleFavorites(Product $product)
    {
        // Controleer of de gebruiker is ingelogd
        }
        // Haal de ingelogde gebruiker op
        $user = Auth::user();
        $user->favorites()->toggle($product->id);

        return response()->json(['success' => true]);
    }