@extends('layouts.app')

@section('content')
<div class="mylist-wrapper">
    <h2>マイリスト（いいねした商品）</h2>

    @if($likedProducts->isEmpty())
        <p>いいねした商品はまだありません。</p>
    @else
        <div class="product-list">
            @foreach($likedProducts as $product)
                <div class="product-item">
                    <a href="{{ route('item.show', $product->id) }}">
                        <img src="{{ $product->img_url }}" alt="{{ $product->name }}">
                        <p>{{ $product->name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
