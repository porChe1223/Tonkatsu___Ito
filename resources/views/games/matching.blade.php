<!DOCTYPE html>
<html lang="Ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>マッチング画面</title>
    <link rel="stylesheet" href="{{ asset('css/matching.blade.css') }}">
</head>

<body>
    <div class="container">
        <h1>マッチング中...</h1>
        <p>他の参加者を待っています...</p>
        <span>{{$room->player_count}}</span>
        <span> /4</span>
    </div>
</body>

<script>
    let isAutoRedirect = false;

    setInterval(function(){
        // サーバーに部屋の状態を確認するリクエストを送る
        fetch('/check-room-status/{{ $room->id }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Room not found');
                }
                return response.json();
            })
            .then(data => {
                // 部屋が満員かどうかを確認
                if (data.isFull) {
                    isAutoRedirect = true;
                    // 部屋が満員になったらプレイ画面にリダイレクト
                    window.location.href = '/gameroom/{{ $room->id }}';
                } else {
                    document.getElementById('participants').textContent = data.player_count; // 取得したプレイヤー数で更新
                }
            })
            .catch(error => {
                console.error('Error fetching room status:', error);
            });
    }, 500); // 1秒ごとにサーバーの状態を確認

    window.addEventListener('beforeunload', (event) => {
        if (!isAutoRedirect && window.location.href !== '/gameroom/{{ $room->id }}') {
            fetch(`{{route('removeMatchingRoom')}}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // CSRFトークンをヘッダーに追加
                    'Content-Type': 'application/json',
                }
            }).then(response => {
                if (!response.ok) {
                    console.error('Failed to remove user from room');
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        }

    });

    window.addEventListener('load', () => {
        isAutoRedirect = false; // ページが読み込まれたら元に戻す
    });
</script>

</html>