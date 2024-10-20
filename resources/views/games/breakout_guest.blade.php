<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ルーム待機画面</title>
    <link rel="stylesheet" href="{{ asset('/css/breakoutroom.blade.css')}}">
</head>
<body>
    <div class="container">
        <h1>部屋番号:
            <span id="room_id">
                {{$room->id}}
            </span>
        </h1>
        <p>他の参加者を待っています...</p>
        <h1>参加者</h1>
            @foreach($participants as $participant)
                    <li>{{ $participant['name']}}</li>
            @endforeach
    </div>
    
</body>
</html>