@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')

@if(session('debug_preview'))
  <pre style="background:#f7f7f7;padding:8px;border:1px solid #ddd;margin-bottom:12px;">
{{ print_r(session('debug_preview'), true) }}
  </pre>
@endif

@php
  $previewPath = session('previewPath');
@endphp

<div class="sell-page">
  <div class="sell-form__content">
    <h2 class="sell-form__heading">商品の出品</h2>

    <form action="{{ route('sell.store') }}" method="POST" class="form" enctype="multipart/form-data">
      @csrf

      @if($previewPath)
        <input type="hidden" name="temp_image" value="{{ $previewPath }}">
      @endif

    <div class="form__group">
    <div class="form__group-title">商品画像</div>

    @php $previewPath = session('previewPath'); @endphp

    @if($previewPath)
        <div class="image-preview">
        <img src="{{ asset('storage/' . $previewPath) }}" alt="プレビュー画像">
        </div>
        <input type="hidden" name="temp_image" value="{{ $previewPath }}">
    @endif

    <label class="image-drop" aria-label="画像を選択する">
        <input type="file" name="image" accept=".jpeg,.jpg,.png">
        <span class="image-drop__hint">{{ $previewPath ? '別の画像を選択' : '画像を選択する' }}</span>
    </label>
    @error('image')<p class="form__error">{{ $message }}</p>@enderror

    <div style="margin-top:8px">
        <button type="submit"
                class="btn btn-secondary"
                formaction="{{ route('sell.preview') }}">
        画像を確認
        </button>
    </div>
    </div>

      <div class="section-title">商品の詳細</div>

      <div class="form__group">
        <div class="form__group-title">カテゴリー</div>
        <div class="pill-list">
          @php $oldCats = collect(old('categories', [])); @endphp
          @foreach ($categories as $cat)
            <label class="pill-check">
              <input type="checkbox" name="categories[]" value="{{ $cat }}" {{ $oldCats->contains($cat) ? 'checked' : '' }}>
              <span>{{ $cat }}</span>
            </label>
          @endforeach
        </div>
        @error('categories')<p class="form__error">{{ $message }}</p>@enderror
      </div>

      <div class="form__group">
        <div class="form__group-title">商品の状態</div>
        <select name="condition" class="select">
          <option value="" hidden>選択してください</option>
          @foreach ($conditions as $c)
            <option value="{{ $c }}" @selected(old('condition') === $c)>{{ $c }}</option>
          @endforeach
        </select>
        @error('condition')<p class="form__error">{{ $message }}</p>@enderror
      </div>

      <div class="section-title">商品名と説明</div>

      <div class="form__group">
        <div class="form__group-title">商品名</div>
        <input type="text" name="name" class="input" value="{{ old('name') }}">
        @error('name')<p class="form__error">{{ $message }}</p>@enderror
      </div>

      <div class="form__group">
        <div class="form__group-title">ブランド名</div>
        <input type="text" name="brand" class="input" value="{{ old('brand') }}">
      </div>

      <div class="form__group">
        <div class="form__group-title">商品の説明</div>
        <textarea name="description" class="textarea" rows="5">{{ old('description') }}</textarea>
        @error('description')<p class="form__error">{{ $message }}</p>@enderror
      </div>

      <div class="form__group">
        <div class="form__group-title">販売価格</div>
        <div class="price-input">
          <span class="price-prefix">¥</span>
          <input type="number" name="price" class="input input--price" value="{{ old('price') }}" min="0" step="1" inputmode="numeric">
        </div>
        @error('price')<p class="form__error">{{ $message }}</p>@enderror
      </div>

<div class="form__button" style="display:flex; gap:12px; justify-content:center;">
    <button type="submit"
            class="form__button-preview"
            formaction="{{ route('sell.preview') }}">
        プレビュー表示
    </button>

    <button type="submit" class="form__button-submit">出品する</button>
</div>

    </form>
  </div>
</div>
@endsection
