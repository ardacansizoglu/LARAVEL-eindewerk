@extends('layouts.default')

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Checkout</h1>

        <div class="grid grid-cols-3 gap-8">
            <!-- Shipping Form -->
            <div class="col-span-2">
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-xl font-semibold mb-4">Verzendgegevens</h2>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Voornaam</label>
                                <input type="text" name="voornaam"
                                    value="{{ old('voornaam', auth()->user()->voornaam) }}"
                                    class="w-full border border-gray-300 rounded-md p-2 @error('voornaam') border-red-500 @enderror">
                                @error('voornaam')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Achternaam</label>
                                <input type="text" name="achternaam"
                                    value="{{ old('achternaam', auth()->user()->achternaam) }}"
                                    class="w-full border border-gray-300 rounded-md p-2 @error('achternaam') border-red-500 @enderror">
                                @error('achternaam')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Straat</label>
                                <input type="text" name="straat" value="{{ old('straat', auth()->user()->straat) }}"
                                    class="w-full border border-gray-300 rounded-md p-2 @error('straat') border-red-500 @enderror">
                                @error('straat')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Huisnummer</label>
                                <input type="text" name="huisnummer"
                                    value="{{ old('huisnummer', auth()->user()->huisnummer) }}"
                                    class="w-full border border-gray-300 rounded-md p-2 @error('huisnummer') border-red-500 @enderror">
                                @error('huisnummer')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Postcode</label>
                                <input type="text" name="postcode"
                                    value="{{ old('postcode', auth()->user()->postcode) }}"
                                    class="w-full border border-gray-300 rounded-md p-2 @error('postcode') border-red-500 @enderror">
                                @error('postcode')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Woonplaats</label>
                                <input type="text" name="woonplaats"
                                    value="{{ old('woonplaats', auth()->user()->woonplaats) }}"
                                    class="w-full border border-gray-300 rounded-md p-2 @error('woonplaats') border-red-500 @enderror">
                                @error('woonplaats')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-orange-500 text-white py-2 rounded-md hover:bg-orange-600 mt-6">
                            BESTELLING AFRONDEN
                        </button>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-100 p-6 rounded-lg shadow-md h-fit">
                <h2 class="text-xl font-semibold mb-4">Bestelling overzicht</h2>
                @foreach ($products as $product)
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <p class="font-medium">{{ $product->name }}</p>
                            <p class="text-sm text-gray-600">Maat: {{ $product->pivot->size }}</p>
                            <p class="text-sm text-gray-600">Aantal: {{ $product->pivot->quantity }}</p>
                        </div>
                        <p class="font-medium">€{{ number_format($product->price * $product->pivot->quantity, 2) }}</p>
                    </div>
                @endforeach

                <div class="border-t mt-4 pt-4">
                    <p class="flex justify-between"><span>Subtotaal</span> <span>€{{ number_format($subtotal, 2) }}</span>
                    </p>
                    <p class="flex justify-between"><span>Verzending</span> <span>€{{ number_format($shipping, 2) }}</span>
                    </p>
                    <p class="flex justify-between font-bold mt-2">
                        <span>Totaal (incl. BTW)</span>
                        <span>€{{ number_format($total, 2) }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
