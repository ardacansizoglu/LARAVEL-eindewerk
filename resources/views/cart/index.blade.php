@extends('layouts.default')

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Shopping Cart</h1>

        @if ($products->isEmpty())
            <p class="text-gray-500">Your shopping cart is empty.</p>
        @else
            <div class="grid grid-cols-3 gap-8">

                <!-- Shopping Cart Items -->
                <div class="col-span-2">
                    <h4 class="text-lg font-semibold mb-4">{{ $products->count() }} producten</h4>
                    @foreach ($products as $product)
                        <div class="flex items-center justify-between bg-grey shadow-md rounded-lg p-4 mb-4">

                            <!-- Product Image -->
                            <div class="w-20 h-20">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
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
                                    <button type="submit" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Quantity Input -->
                            <div class="quantity-input mb-4">
                                <form action="{{ route('cart.update', $product->id) }}" method="POST"
                                    class="flex items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $product->pivot->quantity }}"
                                        min="1" class="w-16 border border-gray-300 rounded-md text-center mr-2">
                                    <button type="submit" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Price Details -->
                            <div class="price-details mb-4 text-right">
                                {{-- <p class="text-sm text-gray-600">{{ $product->pivot->quantity }} x
                                    €{{ number_format($product->price, 2) }}</p> --}}
                                <p class="text-lg font-bold">
                                    €{{ number_format($product->price * $product->pivot->quantity, 2) }}</p>
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
                        <p>Subtotal: <span class="float-right">€{{ number_format($subtotal, 2) }}</span></p>
                        <p>Verzending: <span class="float-right">€{{ number_format($shipping, 2) }}</span></p>
                        <p class="font-bold text-lg mt-2">Totaalprijs (inclusief BTW): <span
                                class="float-right">€{{ number_format($total, 2) }}</span></p>
                    </div>

                    <form action="{{ route('order.place') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full bg-pink-500 text-white py-2 rounded-md hover:bg-blue-600">
                            BESTELLING PLAATSEN
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
