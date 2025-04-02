@extends('layouts.default')

@section('title', 'Shopping Cart')

@section('content')
    <h1 class="text-4xl font-semibold mb-4">Shopping Cart</h1>
    @if ($cartItems->isEmpty())
        <p>Your cart is empty.</p>
    @else
        <ul class="space-y-4">
            @foreach ($cartItems as $item)
                <li class="border border-gray-300 rounded p-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold">{{ $item->name }}</h2>
                        <p>Price: €{{ $item->price }}</p>
                        <p>Quantity: {{ $item->pivot->quantity }}</p>
                        <p>Size: {{ $item->pivot->size }}</p>
                    </div>
                    <form action="{{ route('cart.delete', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">Remove</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
