<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ito</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.blade.css') }}">
</head>
<body>
    <div class="container">
        <h1>Ito</h1>
        <p>自分の数字を言葉で表現しよう</p>
        <form action="{{ route('goMatchingRoom') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">ランダムマッチングを開始</button>
        </form>
        <form action="{{ route('goMakeRoom') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">ルームを作成</button>
        </form>
        <form action="{{ route('joinMakeRoom') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">ルームに参加</button>
        </form>
    </div>
</body>
</html>
