<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ルーム待機画面</title>
    <link rel="stylesheet" href="{{ asset('/css/makeroom.blade.css')}}">
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
    </div>
    <script>
        setInterval(fuction(){
            fetch('/check-join-user/{{ $room->id }}')
                .then(response => response.json())
                .then(date => {
                    document.getElementById('joiningUserId').textContent
                    = date.joiningUserId;//ほんとはIDじゃなくて名前を出したい
                    if (date.isFull) {
                       //2人集まったら開始ボタン出現させたい
                    }
                })
                .catch(error => {
                    console.error('Error fetching room status:', error);
                });
        }, 5000); // 5秒ごとにサーバーの状態を確認
    </script>
</body>
</html>