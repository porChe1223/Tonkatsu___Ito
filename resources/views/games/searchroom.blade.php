<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>部屋番号検索画面</title>
    <link rel="stylesheet" href="{{ asset('/css/searchroom.blade.css')}}">
</head>
<body>
    <div class="container">
        <h1>部屋番号を入力して下さい</h1>
        <input type="number" id="roomId" required>
        <button type="submit" class="btn btn-primary">検索</button>
        <!-- 部屋番号(=roomid)を入力してその部屋に参加させたい -->
</body>
</html>