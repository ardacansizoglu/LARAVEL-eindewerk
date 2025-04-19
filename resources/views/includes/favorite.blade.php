@php
    $isFavorite = auth()->user()->favorites()->where('product_id', $product->id)->exists();
@endphp

@if ($isFavorite)
    <i class="fas fa-heart"></i>
@else
    <i class="far fa-heart"></i>
@endif
