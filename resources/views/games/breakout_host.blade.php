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
        <p font-weight="bold">{{$room->player_count}}</p>
    </div>
</body>

<script>
    // Bladeで生成されたルートをJavaScript側で使用できるように設定
    const checkRoomStatusUrl = "{{ url('/check-room-status/' . $room->id) }}";
    const redirectToGameRoomHostUrl = "{{ route('goGameRoomHost', ['room' => $room->id]) }}";

    setInterval(function() {
        // サーバーに部屋の状態を確認するリクエストを送る
        fetch(checkRoomStatusUrl)
            .then(response => response.json())
            .then(data => {
                // 部屋が満員かどうかを確認
                if (data.isFull) {
                    // 部屋が満員になったらプレイ画面にリダイレクト
                    window.location.href = redirectToGameRoomHostUrl;
                }
            })
            .catch(error => {
                console.error('Error fetching room status:', error);
            });
    }, 2000); // 2秒ごとにサーバーの状態を確認
</script>
</html>