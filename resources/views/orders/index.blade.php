@extends('layouts.default')

@section('title', 'Checkout')

@section('content')

    <h1 class="text-4xl font-semibold mb-8">Mijn bestellingen</h1>

    <div class="space-y-4">
        @foreach ($orders as $order)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <a href="{{ route('orders.show', $order) }}" class="block p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="font-medium">Bestelling van {{ $order->created_at->format('d M Y') }}</h2>
                            <p class="text-sm text-gray-600">{{ $order->products->count() }}
                                {{ $order->products->count() == 1 ? 'product' : 'producten' }}</p>
                        </div>
                        <div class="text-gray-400">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    @if ($orders->isEmpty())
        <p class="text-gray-500 text-center py-8">Je hebt nog geen bestellingen geplaatst.</p>
    @endif
    </div>
@endsection
