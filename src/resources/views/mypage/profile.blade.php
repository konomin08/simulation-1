@extends('layouts.app')

@section('body-class', 'profile-page')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-form__wrapper">
  <div class="profile-form__content">
    <h2 class="profile-form__heading">プロフィール設定</h2>

    @if(session('status'))
      <p class="form__status">{{ session('status') }}</p>
    @endif

    <form action="{{ route('profile.update') }}"
          method="POST"
          enctype="multipart/form-data"
          class="form">
      @csrf

      <div class="form__group profile-image">
        @if ($user->profile_image)
          <img src="{{ asset('storage/' . $user->profile_image) }}"
               alt="プロフィール画像"
               class="profile-image__preview">
        @else
          <div class="profile-image__placeholder"></div>
        @endif
        <input type="file"
               name="profile_image"
               class="profile-image__input">
        @error('profile_image')
          <p class="form__error">{{ $message }}</p>
        @enderror
      </div>

      <div class="form__group">
        <label class="form__group-title">ユーザー名</label>
        <div class="form__input--text">
          <input type="text"
                 name="name"
                 value="{{ old('name', $user->name) }}">
        </div>
        @error('name')
          <p class="form__error">{{ $message }}</p>
        @enderror
      </div>

      <div class="form__group">
        <label class="form__group-title">郵便番号</label>
        <div class="form__input--text">
          <input type="text"
                 name="zipcode"
                 value="{{ old('zipcode', $user->zipcode ?? '') }}">
        </div>
        @error('zipcode')
          <p class="form__error">{{ $message }}</p>
        @enderror
      </div>

      <div class="form__group">
        <label class="form__group-title">住所</label>
        <div class="form__input--text">
          <input type="text"
                 name="address"
                 value="{{ old('address', $user->address ?? '') }}">
        </div>
        @error('address')
          <p class="form__error">{{ $message }}</p>
        @enderror
      </div>

      <div class="form__group">
        <label class="form__group-title">建物名</label>
        <div class="form__input--text">
          <input type="text"
                 name="building"
                 value="{{ old('building', $user->building ?? '') }}">
        </div>
        @error('building')
          <p class="form__error">{{ $message }}</p>
        @enderror
      </div>

      <div class="form__button">
        <button type="submit" class="form__button-submit">
          更新する
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

