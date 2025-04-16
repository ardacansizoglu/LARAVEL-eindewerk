@extends('layouts.default')

@section('content')

    @if ($products->isEmpty())
        <p>Your shopping cart is empty.</p>
    @else
        <div class="row">
            {{-- Cart Items Section --}}
            <div class="col-md-8">
                <div class="card mb-3 cart-item">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <h5>Product</h5>
                        </div>
                        <div class="col-md-4">
                            <h5>Details</h5>
                        </div>
                        <div class="col-md-3">
                            <h5>Prijs</h5>
                        </div>
                        <div class="col-md-3">
                            <h5>Acties</h5>
                        </div>
                        <h4>Shopping Cart ({{ $products->count() }} producten)</h4>
                        @foreach ($products as $product)
                            <div class="card mb- cart-item">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    {{-- Product Image --}}
                                    <div class="col-md-2 product-image">
                                        <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}"
                                            style="width: 100px; height: auto;">
                                    </div>

                                    {{-- Product Details --}}
                                    <div class="col-md-4 product-details">
                                        <h5>{{ $product->brand->name }}</h5>
                                        <p>{{ $product->name }}</p>
                                        <p>Maat: {{ $product->pivot->size }}</p>
                                    </div>

                                    {{-- Quantity and Price --}}
                                    < <div class="col-md-2 product-quantity">
                                        <form action="{{ route('cart.update', $product->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $product->pivot->quantity }}"
                                                min="1" class="form-control mb-2" style="width: 80px;">
                                            <input type="hidden" name="size" value="{{ $product->pivot->size }}">
                                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                        </form>
                                        <p>€{{ number_format($product->price, 2) }}</p>
                                        <p><strong>€{{ number_format($product->price * $product->pivot->quantity, 2) }}</strong>
                                        </p>
                                </div>

                                <!-- Price Details -->
                                <div class="col-md-2 product-price">
                                    <p>3 x €69.95</p>
                                    <p><strong>€209.85</strong></p>
                                </div>

                                {{-- Remove Button --}}
                                <div class="col-md-2 product-remove">
                                    <form action="{{ route('cart.delete', $product->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Verwijderen</button>
                                    </form>
                                </div>
                            </div>
                    </div>
    @endforeach
    </div>

    {{-- Summary Section --}}
    <div class="col-md-8">
        <h4>Total Price</h4>
        <p>Subtotal: €{{ number_format($subtotal, 2) }}</p>
        <p>Shipping: €{{ number_format($shipping, 2) }}</p>
        <p><strong>Total: €{{ number_format($total, 2) }}</strong></p>

        {{-- Discount Code Section --}}
        <form action="{{ route('cart.set-discount') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="discount_code">Discount Code</label>
                <input type="text" name="code" id="discount_code" class="form-control" placeholder="Enter code">
            </div>
            <button type="submit" class="btn btn-success btn-sm mt-2">Apply</button>
        </form>

        {{-- Place Order Button --}}
        <form action="{{ route('order.place') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-warning btn-block" {{ $products->isEmpty() ? 'disabled' : '' }}>
                Place Order
            </button>
        </form>
    </div>
    </div>
    @endif
@endsection
