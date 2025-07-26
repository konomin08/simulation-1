@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}"> {{-- CSSのファイル名に合わせて --}}
@endsection

@section('content')
<div class="register-page">
  <div class="register-form__content">
    <h1 class="register-form__heading">住所の変更</h1>

    <form class="form" method="POST" action="{{ route('purchase.address.update', ['item_id' => $item_id]) }}">
      @csrf

      <div class="form__group">
        <div class="form__group-title">郵便番号</div>
        <div class="form__error">
          @error('zipcode') {{ $message }} @enderror
        </div>
        <div class="form__input--text">
          <input type="text" name="zipcode" value="{{ old('zipcode', $address->zipcode) }}">
        </div>
      </div>

      <div class="form__group">
        <div class="form__group-title">住所</div>
        <div class="form__error">
          @error('address') {{ $message }} @enderror
        </div>
        <div class="form__input--text">
          <input type="text" name="address" value="{{ old('address', $address->address) }}">
        </div>
      </div>

      <div class="form__group">
        <div class="form__group-title">建物名</div>
        <div class="form__error">
          @error('building') {{ $message }} @enderror
        </div>
        <div class="form__input--text">
          <input type="text" name="building" value="{{ old('building', $address->building) }}">
        </div>
      </div>

      <div class="form__button">
        <button type="submit" class="form__button-submit">更新する</button>
      </div>
    </form>
  </div>
</div>
@endsection
