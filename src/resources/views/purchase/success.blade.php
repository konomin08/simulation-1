@extends('layouts.app')
@section('content')
<div class="container" style="padding:24px;">
  <h2>手続きが完了しました</h2>
  <p>ご購入ありがとうございました。</p>
  <div style="margin-top:16px; display:flex; gap:8px;">
    <a href="{{ route('index') }}" class="btn">トップへ戻る</a>
    <a href="{{ route('mypage') }}" class="btn">マイページへ</a>
  </div>
</div>
@endsection
