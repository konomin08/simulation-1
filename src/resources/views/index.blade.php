@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

<div class="container">

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

    <div class="product-list">
        @forelse ($products as $product)
            <div class="product-item">
                <a href="{{ route('item.show', ['item_id' => $product->id]) }}">
                    @php
                        $raw = $product->image_path ?? $product->img_url ?? null;

                        if ($raw) {
                            if (Str::startsWith($raw, ['http://','https://','//'])) {
                                $url = $raw;
                            } elseif (Str::startsWith($raw, 'storage/')) {
                                $url = asset($raw);
                            } else {
                                $url = asset('storage/' . ltrim($raw, '/'));
                            }
                        } else {
                            $url = null;
                        }
                    @endphp

                    @if ($url)
                        <img src="{{ $url }}" alt="{{ $product->name }}" class="product-image">
                    @else
                        <div class="product-image no-image">画像なし</div>
                    @endif
                </a>

                <p>{{ $product->name }}</p>

                @if (!empty($product->is_sold))
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
