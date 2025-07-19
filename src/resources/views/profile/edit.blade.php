@extends('layouts.app')

@section('content')
<div class="profile-setting">
    <h2>プロフィール設定</h2>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label>プロフィール画像</label><br>
            @if ($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" width="100">
            @else
                <img src="{{ asset('images/default.png') }}" alt="デフォルト画像" width="100">
            @endif
            <input type="file" name="profile_image">
        </div>

        <div>
            <label>ユーザー名</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}">
        </div>

        <div>
            <label>郵便番号</label>
            <input type="text" name="zipcode" value="{{ old('zipcode', $user->zipcode) }}">
        </div>

        <div>
            <label>住所</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}">
        </div>

        <div>
            <label>建物名</label>
            <input type="text" name="building" value="{{ old('building', $user->building) }}">
        </div>

        <button type="submit">更新する</button>
    </form>
</div>
@endsection
