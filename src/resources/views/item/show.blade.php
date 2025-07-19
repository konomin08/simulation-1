@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="item-detail__wrapper">
  <div class="item-detail__container">

    {{-- 左: 商品画像 --}}
    <div class="item-detail__image">
      <img src="{{ $product->img_url }}" alt="{{ $product->name }}">
    </div>

    {{-- 右: 商品情報 --}}
    <div class="item-detail__info">
      <h1 class="item-detail__title">{{ $product->name }}</h1>
      <p class="item-detail__brand">{{ $product->brand ?? 'ブランド不明' }}</p>
      <p class="item-detail__price">¥{{ number_format($product->price) }} <span>（税込）</span></p>

    <div class="item-detail__actions">
        <form action="{{ route('like.toggle', $product->id) }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" style="background:none; border:none; cursor:pointer;">
            @if (auth()->check() && auth()->user()->likedProducts->contains($product->id))
            <img src="{{ asset('images/star-filled.png') }}" alt="いいね済み" class="like-icon-image">
            @else
            <img src="{{ asset('images/star-empty.png') }}" alt="いいね" class="like-icon-image">
            @endif
        </button>
        </form>
        {{ $product->likedUsers->count() }}
        <!-- コメントアイコン（吹き出し） -->
        <img src="{{ asset('images/comment-icon.png') }}" alt="コメント数" class="comment-icon-image">
        {{ $product->comments_count ?? 0 }}
    </div>

      <form action="{{ route('purchase.store', ['item_id' => $product->id]) }}" method="POST">
        @csrf
        <button type="submit" class="item-detail__buy-button">購入手続きへ</button>
      </form>

      <div class="item-detail__section">
        <h3>商品説明</h3>
        <p>{{ $product->description }}</p>
      </div>

      <div class="item-detail__section">
        <h3>商品の情報</h3>
        <p><strong>カテゴリー:</strong> {{ $product->category->name ?? '未設定' }}</p>
        <p><strong>商品の状態:</strong> {{ $product->condition ?? '未設定' }}</p>
      </div>

        <div class="item-detail__section">
        <h3>コメント ({{ $product->comments->count() ?? 0 }})</h3>

        @foreach ($product->comments ?? [] as $comment)
        <div class="comment-item">
            <img
            src="{{ asset('storage/' . ($comment->user->profile_image ?? 'default.png')) }}" 
            alt="{{ $comment->user->name ?? 'ゲスト' }}"
            class="comment-profile-image">

            <div class="comment-text">
            <span class="comment-username">{{ $comment->user->name ?? 'ゲスト' }}</span>
            <p>{{ $comment->content }}</p>
            </div>
        </div>
        @endforeach


        <form action="{{ route('comment.store', $product->id) }}" method="POST" style="margin-top: 20px;">
            @csrf
            <label for="comment">商品へのコメント</label><br>
            <textarea name="comment" rows="4" cols="50"></textarea><br>
            <button type="submit" class="item-detail__comment-button">コメントする</button>
        </form>
        </div>


    </div>
  </div>
</div>
@endsection
