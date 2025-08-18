@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
@php
  use Illuminate\Support\Str;

  $raw = $product->image_path ?? $product->img_url ?? null;
  if ($raw) {
      if (Str::startsWith($raw, ['http://','https://','//'])) {
          $imageUrl = $raw;
      } elseif (Str::startsWith($raw, 'storage/')) {
          $imageUrl = asset($raw);
      } else {
          $imageUrl = asset('storage/' . ltrim($raw,'/'));
      }
  } else {
      $imageUrl = null;
  }
@endphp

<div class="item-detail__wrapper">
  <div class="item-detail__container">

    <div class="item-detail__image">
      @if($imageUrl)
        <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
      @else
        <div class="no-image">画像なし</div>
      @endif
    </div>

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
        <p><strong>カテゴリー:</strong>
          @if($product->categories && $product->categories->isNotEmpty())
            {{ $product->categories->pluck('name')->join(' / ') }}
          @else
            未設定
          @endif
        </p>
        <p><strong>商品の状態:</strong> {{ $product->condition ?? '未設定' }}</p>
      </div>

      <div class="item-detail__section">
        <h3>コメント ({{ $product->comments->count() ?? 0 }})</h3>

        @foreach ($product->comments ?? [] as $comment)
          @php
            $uimg = $comment->user->profile_image ?? null;
            if ($uimg) {
                $uimgUrl = Str::startsWith($uimg, ['http://','https://','//'])
                            ? $uimg
                            : asset('storage/' . ltrim($uimg,'/'));
            } else {
                $uimgUrl = asset('images/default.png');
            }
          @endphp

          <div class="comment-item">
            <img src="{{ $uimgUrl }}"
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
          <textarea name="comment" rows="4" cols="50">{{ old('comment') }}</textarea>
          @if ($errors->has('comment'))
            <p class="form__error">{{ $errors->first('comment') }}</p>
          @endif
          <button type="submit" class="item-detail__buy-button">コメントする</button>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection
