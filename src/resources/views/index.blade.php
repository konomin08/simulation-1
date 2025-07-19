@extends('layouts.app')

@section('content')
<div class="container">

    <!-- タブ切り替え -->
    <div class="tab-switch">
        <a href="{{ request('search') ? url('/') . '?search=' . urlencode(request('search')) : url('/') }}"
           class="tab-link {{ $page !== 'mylist' ? 'active' : '' }}">
            おすすめ
        </a>
        <a href="{{ route('mylist', ['search' => request('search')]) }}"
           class="tab-link {{ $page === 'mylist' ? 'active' : '' }}">
            マイリスト
        </a>
    </div>

    <!-- 商品一覧 -->
    <div class="product-list">
        @forelse ($products as $product)
            <div class="product-item">
                <a href="{{ route('item.show', ['item_id' => $product->id]) }}">
                    <img src="{{ $product->img_url }}" alt="{{ $product->name }}" class="product-image">
                </a>
                <p>{{ $product->name }}</p>
                @if ($product->is_sold)
                    <p class="sold-text">Sold</p>
                @endif
            </div>
        @empty
            @if ($page === 'mylist')
                <p>※ いいねした商品はありません。</p>
            @else
                <p>※ 商品が見つかりません。</p>
            @endif

        @endforelse
    </div>
</div>
@endsection
