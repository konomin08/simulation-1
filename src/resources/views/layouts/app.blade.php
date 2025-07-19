<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COACHTECH</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  @yield('css')
</head>

<body class="@yield('body-class')">
  @php
    // 会員登録ページ or ログインページだけの判定
    $isAuthPage = request()->is('login') || request()->is('register');
  @endphp

  <header class="header">
    <div class="header__inner">
      <!-- ロゴは共通 -->
      <div class="header-logo">
        <a href="{{ url('/') }}">
          <img src="{{ asset('images/logo.svg') }}" alt="サイトロゴ">
        </a>
      </div>

      <!-- 認証ページ以外だけナビと検索フォームを表示 -->
      @unless($isAuthPage)
        <div class="header-search">
          <form action="{{ route('index') }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}"
                  placeholder="なにをお探しですか？">
            @if (request('page'))
              <input type="hidden" name="page" value="{{ request('page') }}">
            @endif
          </form>
        </div>

        <nav class="header-nav">
          <ul>
            @if (!Auth::check())
              <li><a href="/login">ログイン</a></li>
              <li><a href="/mypage">マイページ</a></li>
              <li><a href="/sell">出品</a></li>
            @else
              <li><a href="/mypage">マイページ</a></li>
              <li><a href="/sell">出品</a></li>
              <li>
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="header-nav__button">ログアウト</button>
                </form>
              </li>
            @endif
          </ul>
        </nav>
      @endunless

    </div>
  </header>

  <main>
    @yield('content')
  </main>
</body>
</html>
