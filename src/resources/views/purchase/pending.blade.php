@extends('layouts.app')

@section('content')
<div class="container" style="padding:24px;">
  <h2>お支払い待ち</h2>
  <p>コンビニでのお支払い番号をメールで送信しています。店頭でお支払いください。</p>

  @if (session('status'))
    <p style="color:#555">{{ session('status') }}</p>
  @endif

  <form action="{{ route('purchase.check', ['item_id'=>$product->id]) }}" method="POST" style="margin-top:16px;">
    @csrf
    <input type="hidden" name="session_id" value="{{ $sessionId }}">
    <button type="submit" class="btn">支払い状態を更新</button>
  </form>

  <div style="margin-top:12px;">
    <a href="{{ route('mypage') }}">マイページへ</a>
  </div>
</div>
@endsection
