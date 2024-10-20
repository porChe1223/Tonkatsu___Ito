<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Ito</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="stylesheet" href="{{ asset('css/welcome.blade.css') }}">

        <!-- Styles -->
        <style>
        </style>
    </head>
    <body>
        <div class="container">
        <h1>Ito</h1>
        <p>言葉で当てる 1~100！意思疎通ゲーム</p>
        @if (Route::has('login'))
            @auth
                <div><a
                    href="{{ url('/home') }}"
                    class="btn"
                >
                    ゲームを始める
                </a></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link">ログアウト</button>
                </form>
            @else
                <div><a
                    href="{{ route('login') }}"
                    class="btn"
                >
                    ログイン
                </a></div>

                @if (Route::has('register'))
                    <div><a
                        href="{{ route('register') }}"
                        class="btn"
                    >
                        新規登録
                    </a></div>
                @endif
            @endauth
        @endif
        </div>           
    </body>
</html>
