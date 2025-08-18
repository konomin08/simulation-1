@extends('layouts.app')

@section('content')
<div class="container" style="padding:24px;">
  <h2>支払いがキャンセルされました</h2>
  <a href="{{ route('purchase.show', ['item_id' => $product->id]) }}">購入手続きに戻る</a>
</div>
@endsection
