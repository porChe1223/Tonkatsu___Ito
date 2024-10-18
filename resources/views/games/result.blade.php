<!DOCTYPE html>
<html lang="Ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>結果画面</title>
    <link rel="stylesheet" href="{{ asset('css/result.blade.css') }}">
</head>

<body>
    <div>
        <h1>答え</h1>
        <a href="{{ url('/dashboard') }}"><button type="submit" class="go-result-button">ダッシュボードに戻る</button></a>
        <p>--1--</p>
        @foreach ($usersCardNumbers as $each_number)
        <p class="card-number">{{$each_number}}</p>
        @endforeach
        <p>--100--</p>
</body>

</html>