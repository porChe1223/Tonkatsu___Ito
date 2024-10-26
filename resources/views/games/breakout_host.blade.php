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

        <button id="startButton" style="display: none;">ゲーム開始</button>
    </div>
</body>

<script>
    let isAutoRedirect = false;

    document.getElementById('startButton').addEventListener('click', function () {
        fetch('/start-game/{{ $room->id }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRFトークン
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Game started');
            }
        })
        .catch(error => console.error('Error starting game:', error));
    });

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
                    window.location.href = '/gameroom_host/{{ $room->id }}';
                } else if (data.isReady) { // ゲームが始めれるかどうかを確認
                    isAutoRedirect = true;
                    document.getElementById('startButton').style.display = 'block';
                } else {
                    getElementById('participants').textContent = data.participants; // 取得したプレイヤーを更新
                }
            })
            .catch(error => {
                console.error('Error fetching room status:', error);
            });
    }, 500); // 1秒ごとにサーバーの状態を確認

    window.addEventListener('beforeunload', (event) => {
        if (!isAutoRedirect && window.location.pathname !== `/result_host/{{ $room->id }}`) {
            fetch(`{{ route('removeBreakoutRoomHost') }}`, {
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