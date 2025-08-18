@extends('layouts.app')

@section('body-class', 'profile-page')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
@php
  /** @var \App\Models\User $user */
  $tab = request('tab', 'sell');
  $items = $tab === 'purchase' ? $purchasedItems : $sellingItems;
@endphp

<div class="mypage">
  <div class="mypage__hero">
    <div class="mypage__avatar">
      @if ($user->profile_image)
        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像">
      @else
        <div class="mypage__avatar--placeholder"></div>
      @endif
    </div>

    <div class="mypage__name">{{ $user->name }}</div>

    <div class="mypage__edit">
      <a href="{{ route('profile.edit') }}" class="btn-outline-red">プロフィールを編集</a>
    </div>
  </div>

    <div class="mypage__tabs">
    <a href="{{ route('mypage', ['tab' => 'sell']) }}"
        class="tab-link tab-link--sell {{ $tab === 'sell' ? 'is-active' : '' }}">
        出品した商品
    </a>
    <a href="{{ route('mypage', ['tab' => 'purchase']) }}"
        class="tab-link tab-link--purchase {{ $tab === 'purchase' ? 'is-active' : '' }}">
        購入した商品
    </a>
    </div>

  <div class="mypage__grid">
    @forelse($items as $item)
      @php
        $product = $item->product ?? $item;
        $img = $product->image_path ?? null;
      @endphp
      <a class="product-card" href="{{ route('item.show', ['item_id' => $product->id]) }}">
        <div class="product-card__image">
          @if($img)
            <img src="{{ asset('storage/' . $img) }}" alt="{{ $product->name }}">
          @else
            <div class="product-card__placeholder">商品画像</div>
          @endif
        </div>
        <div class="product-card__name">{{ $product->name }}</div>
      </a>
    @empty
      <div class="mypage__empty">
        {{ $tab === 'sell' ? '出品した商品はまだありません。' : '購入した商品はまだありません。' }}
      </div>
    @endforelse
  </div>
</div>
@endsection
