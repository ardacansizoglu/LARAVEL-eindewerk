@extends('layouts.default')

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Shopping Cart</h1>

        @if ($products->isEmpty())
            <p class="text-gray-500">Uw winkelwagen is leeg.</p>
        @else
            <div class="grid grid-cols-3 gap-8">

                <!-- Shopping Cart Items -->
                <div class="col-span-2">
                    <h4 class="text-lg font-semibold mb-4">{{ $products->count() }} producten</h4>
                    @foreach ($products as $product)
                        <div class="flex items-center justify-between bg-grey shadow-md rounded-lg p-4 mb-4">

                            <!-- Product Image -->
                            <div class="w-20 h-20">
                                <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover rounded-md">
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1 ml-4">
                                <h5 class="text-sm font-bold">{{ $product->brand->name }}</h5>
                                <p class="text-sm text-gray-600">{{ $product->name }}</p>
                            </div>


                            {{-- Size dropdown --}}
                            <div class="product-size mb-4 flex items-center">
                                <span class="text-sm font-bold mr-2">Maat:</span>
                                <form action="{{ route('cart.update', $product->id) }}" method="POST"
                                    class="flex items-center">
                                    @csrf
                                    @method('PUT')
                                    <select name="size"
                                        class="border border-gray-300 rounded-md p-2 text-sm text-gray-600 mr-2">
                                        @for ($i = 35; $i <= 46; $i++)
                                            <option value="{{ $i }}"
                                                {{ $product->pivot->size == $i ? 'selected' : '' }}>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </form>
                            </div>

                            <!-- Quantity Input -->
                            <div class="quantity-input mb-4">
                                <form action="{{ route('cart.update', $product->id) }}" method="POST"
                                    class="flex items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $product->pivot->quantity }}"
                                        min="1" class="w-16 border border-gray-300 rounded-md text-center mr-2"
                                        data-price="{{ $product->price }}" data-product-id="{{ $product->id }}"
                                        onchange="updatePrice(this)">
                                </form>
                            </div>

                            <!-- Price Details -->
                            <div class="price-details mb-4 text-right">
                                <p class="text-lg font-bold" id="price-{{ $product->id }}">
                                    €{{ number_format($product->price * $product->pivot->quantity, 2) }}
                                </p>
                            </div>

                            <!-- Remove Button -->
                            <div class=" mb-5 text-right">
                                <form action="{{ route('cart.delete', $product->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-pink-500 hover:text-blue-700 font-bold">Verwijderen</button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <!--Verwachte levering plaats -->
                    <div class="bg-gray-100 p-4 rounded-lg mt-4">
                        <h4 class="text-sm font-semibold">Verwachte levering</h4>
                        <p class="text-sm text-gray-600">Wed 06 April - Thu 07 April</p>
                    </div>
                </div>

                <!-- Summary Section -->
                <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                    <h4 class="text-lg font-semibold mb-4">Kortingscode</h4>
                    <form action="{{ route('cart.set-discount') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="flex items-center">
                            <input type="text" name="code" placeholder="CODE"
                                class="w-full border border-gray-300 rounded-md p-2 mr-2">
                            <button type="submit" class="text-pink-500 hover:text-blue-700">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </form>

                    <h4 class="text-lg font-semibold mb-4">Totaal prijs</h4>
                    <div class="text-sm text-gray-600">
                        <p>Subtotal: <span class="float-right" data-subtotal>€{{ number_format($subtotal, 2) }}</span></p>
                        <p>Verzending: <span class="float-right">€{{ number_format($shipping, 2) }}</span></p>
                        <p class="font-bold text-lg mt-2">Totaalprijs (inclusief BTW):
                            <span class="float-right" data-total>€{{ number_format($total, 2) }}</span>
                        </p>
                    </div>

                    <a href="{{ route('orders.checkout') }}"
                        class="w-full bg-orange-500 text-white py-2 rounded-md hover:bg-orange-600 text-center block">
                        BESTELLING PLAATSEN
                    </a>
                </div>
            </div>
        @endif
    </div>
    <script>
        function updatePrice(input) {
            const quantity = parseInt(input.value);
            const price = parseFloat(input.dataset.price);
            const productId = input.dataset.productId;

            // Calculate new total
            const total = quantity * price;

            // Update price display
            document.getElementById(`price-${productId}`).textContent =
                '€' + total.toFixed(2);

            // Update subtotal and total
            updateTotals();
        }

        function updateTotals() {
            let subtotal = 0;
            const shipping = {{ $shipping }}; // Get shipping cost from PHP

            // Calculate new subtotal
            document.querySelectorAll('input[name="quantity"]').forEach(input => {
                const quantity = parseInt(input.value);
                const price = parseFloat(input.dataset.price);
                subtotal += quantity * price;
            });

            // Update subtotal display
            document.querySelector('[data-subtotal]').textContent =
                '€' + subtotal.toFixed(2);

            // Update total display
            const total = subtotal + shipping;
            document.querySelector('[data-total]').textContent =
                '€' + total.toFixed(2);
        }
    </script>
@endsection
