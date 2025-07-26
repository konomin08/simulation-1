@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">

  <div class="purchase-item">
    <!-- 左側：商品画像・情報＋支払い選択＋住所 -->
    <div class="purchase-item-left">
      <img src="{{ $product->img_url }}" alt="{{ $product->name }}" class="purchase-item__image">

      <div class="purchase-item__info">
        <h3 class="purchase-item__name">{{ $product->name }}</h3>
        <p class="purchase-item__price">¥{{ number_format($product->price) }}（税込）</p>

        <!-- ライン -->
        <hr class="payment-divider">

        <!-- 支払い方法選択欄 -->
        <div class="payment-method">
          <label for="payment">支払い方法</label>
          <select name="payment_method" id="payment" class="payment-select" required>
            <option value="" selected disabled>選択してください</option>
            <option value="convenience_store">コンビニ支払い</option>
            <option value="credit_card">カード支払い</option>
          </select>
        </div>

        <hr class="section-divider">

        <!-- 配送先 -->
        <div class="purchase-user-address">
        <div class="address-header">
            <h4>配送先</h4>
            <a href="{{ url('/purchase/address/' . $product->id) }}" class="change-address-link">変更する</a>
        </div>

        @if ($address)
            <p>{{ $address->zipcode }} {{ $address->address }} {{ $address->building }}</p>
        @else
            <p>{{ $user->zipcode }} {{ $user->address }} {{ $user->building }}</p>
        @endif


      </div>
    </div>

    <!-- 右側：表＋購入ボタン -->
    <div class="purchase-summary-box">
      <div class="summary-row">
        <div class="summary-label">商品代金</div>
        <div class="summary-value">¥{{ number_format($product->price) }}</div>
      </div>
      <div class="summary-row">
        <div class="summary-label">支払い方法</div>
        <div class="summary-value" id="selected-method">選択されていません</div>
      </div>

      <form id="purchase-form" action="{{ route('purchase.store', ['item_id' => $product->id]) }}" method="POST">
        @csrf
        <button type="submit" class="purchase-button">購入する</button>
      </form>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
  const select = document.getElementById('payment');
  const selectedDisplay = document.getElementById('selected-method');

  select.addEventListener('change', function () {
    const text = this.options[this.selectedIndex].text;
    selectedDisplay.textContent = text;
  });
</script>
@endsection
