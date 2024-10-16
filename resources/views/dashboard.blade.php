<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ito</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>ダッシュボード</h1>
        <form action="{{ route('matching') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">マッチングを開始</button>
        </form>
    </div>
</body>
</html>
