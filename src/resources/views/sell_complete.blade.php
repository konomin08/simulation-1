@extends('layouts.app')

@section('content')
<div style="text-align: center; padding: 50px;">
    <h2>✅ 出品しました！</h2>
    <p>ご出品ありがとうございます。</p>
    <a href="{{ route('index') }}"
       style="display: inline-block; padding: 10px 20px; background-color: red; color: white; border-radius: 5px; text-decoration: none;">
        トップページへ戻る
    </a>
</div>
@endsection
