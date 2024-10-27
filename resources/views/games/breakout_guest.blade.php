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
        <p id="participant-title" >参加者</p>
        @foreach($participants as $participant)
                    <li>{{ $participant['name']}}</li>
        @endforeach
    </div>
</body>

<script>
    let isAutoRedirect = false;

    setInterval(function(){
        // サーバーに部屋の状態を確認するリクエストを送る
        fetch('/check-join-user/{{ $room->id }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Room not found');
                }
                return response.json();
            })
            .then(data => {
                if (data.isStarted){ // ゲームがスタートしたらプレイ画面にリダイレクト
                    isAutoRedirect = true;
                    window.location.href = '/gameroom_guest/{{ $room->id }}';
                } else {
                    getElementById('participants').textContent = data.participants; // 取得したプレイヤーを更新
                }
            })
            .catch(error => {
                console.error('Error fetching room status:', error);
            });
    }, 500); // 1秒ごとにサーバーの状態を確認

    setInterval(function(){
        // サーバーに部屋の状態を確認するリクエストを送る
        fetch('/count-participants/{{ $room->id }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Room not found');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('participants').textContent = data.participants; // 取得したプレイヤー数で更新
            })
            .catch(error => {
                console.error('Error fetching room status:', error);
            });
    }, 500); // 1秒ごとにサーバーの状態を確認

    window.addEventListener('beforeunload', (event) => {
        if (!isAutoRedirect && window.location.pathname !== `/gameroom_guest/{{ $room->id }}`) {
            fetch(`{{ route('removeBreakoutRoomGuest') }}`, {
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