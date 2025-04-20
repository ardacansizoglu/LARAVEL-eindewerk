<div class="product-card border border-gray-300 rounded p-4 relative">
    <h2 class="text-lg font-semibold">{{ $product->name }}</h2>
    <p class="text-gray-500">€{{ $product->price }}</p>
    <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="mt-2">

    <form action="{{ route('favorites.toggle', $product->id) }}" method="POST" class="absolute top-2 right-2">
        @csrf
        @method('POST')
        <button type="submit">
            @if (Auth::user() && Auth::user()->favorites->contains($product->id))
                <i class="fa-solid fa-heart text-red-500"></i>
            @else
                <i class="fa-regular fa-heart text-gray-500"></i>
            @endif
        </button>
    </form>

    {{-- Add to Cart Icon --}}
    <form action="{{ route('cart.add', $product->id) }}" method="POST" style="display: inline;">
        @csrf
        <input type="hidden" name="quantity" value="1"> <!-- Default quantity -->
        <input type="hidden" name="size" value="M"> <!-- Default size -->
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-shopping-cart"></i> Add to Cart
        </button>
    </form>

</div>
