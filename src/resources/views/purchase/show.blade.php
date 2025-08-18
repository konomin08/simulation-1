@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">

  <div class="purchase-item">
    <div class="purchase-item-left">
      <img src="{{ $product->img_url }}" alt="{{ $product->name }}" class="purchase-item__image">

      <div class="purchase-item__info">
        <h3 class="purchase-item__name">{{ $product->name }}</h3>
        <p class="purchase-item__price">¥{{ number_format($product->price) }}（税込）</p>

        <hr class="payment-divider">

        <form action="{{ route('purchase.paymentMethod', $product->id) }}" method="POST">
          @csrf
          <div class="payment-method">
            <label for="payment">支払い方法</label>
            <select name="payment_method" id="payment" class="payment-select" onchange="this.form.submit()" required>
              <option value="" disabled {{ session('payment_method') ? '' : 'selected' }}>選択してください</option>
              <option value="convenience_store" {{ session('payment_method') === 'convenience_store' ? 'selected' : '' }}>コンビニ支払い</option>
              <option value="credit_card" {{ session('payment_method') === 'credit_card' ? 'selected' : '' }}>カード支払い</option>
            </select>
          </div>
        </form>

        <hr class="section-divider">

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
    </div>

    <div class="purchase-summary-box">
      <div class="summary-row">
        <div class="summary-label">商品代金</div>
        <div class="summary-value">¥{{ number_format($product->price) }}</div>
      </div>
      <div class="summary-row">
        <div class="summary-label">支払い方法</div>
        <div class="summary-value" id="selected-method">
          @php $method = session('payment_method'); @endphp
          @if ($method === 'convenience_store')
            コンビニ支払い
          @elseif ($method === 'credit_card')
            カード支払い
          @else
            選択されていません
          @endif
        </div>
      </div>

      <form action="{{ route('purchase.store', ['item_id' => $product->id]) }}" method="POST">
        @csrf
        <button type="submit" class="purchase-button">購入する</button>
      </form>
    </div>
  </div>
</div>
@endsection
