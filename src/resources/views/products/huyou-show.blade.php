@extends('layouts.app')

@section('content')
<div class="container">
    <div class="product-detail">
        <h1>{{ $product->name }}</h1>
        <img src="{{ $product->img_url }}" alt="{{ $product->name }}" style="max-width: 300px;">

        <p><strong>価格：</strong> ¥{{ number_format($product->price) }}</p>

        <p><strong>説明：</strong></p>
        <p>{{ $product->description }}</p>

        <form method="POST" action="{{ route('purchase', ['product' => $product->id]) }}">
            @csrf
            <button type="submit">購入する</button>
        </form>
    </div>
</div>
@endsection
