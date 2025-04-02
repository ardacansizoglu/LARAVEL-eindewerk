<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class StoreController extends Controller
{
    public function index()
    {
        $products = Product::all(); // Fetch all products
        return view('store.index', ['products' => $products]);
    }

    public function show(Product $product)
    {
        // Display details of a single product
        return view('store.show', ['product' => $product]);
    }
}