<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameRoom</title>
    <link rel="stylesheet" href="{{ asset('/css/gameroom.blade.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <script src="result.js"></script>

    <div id="all-container">
        <h1>Guest-GameRoom</h1>
        <!-- ゲーム指示 -->
        <div id="instructions-container">
            <div id="title-container">
                <div class="instructions">お題:</div>
                <span id="theme">
                    {{$choosed_Theme->theme}}
                </span>
            </div>

            <div id="card_number-container">
                <div class="instructions">あなたのカード番号</div>
                <span id="card-number">
                    {{$user->card_number}}
                </span>
            </div>
        </div>
        <!-- 以下はチャットルーム -->
        <div id="chat-container" class="mt-4">
            <h2>チャット欄</h2>
            <div id="chat-box" class="border p-3 mb-3" style="height: 300px; overflow-y: scroll;">
                <!-- メッセージがここに表示されます -->
            </div>
            <form id="message-form" class="d-flex">
                @csrf
                <input type="text" id="message" class="form-control me-2" placeholder="メッセージを入力" required>
                <button type="submit" class="btn btn-primary">送信</button>
            </form>
        </div>
    </div>
</body>

<script>
    $(document).ready(function() {
        // 1秒ごとにサーバーからお題を取得して更新
        setInterval(function() {
            let roomId = "{{ $room->id }}"; // 部屋のIDをBladeテンプレートから取得

            $.ajax({
                url: "/get-current-theme/" + roomId, // お題取得用のルート
                type: "GET",
                success: function(response) {
                    // サーバーから取得したお題で表示を更新
                    $('#theme').text(response.currentTheme);
                },
                error: function(xhr) {
                    console.log("お題の取得に失敗しました。");
                }
            });
        }, 2500); // 1秒ごとに実行
    });

    window.addEventListener('beforeunload', (event) => {
        if (!isAutoRedirect && window.location.href !== '/result_guest/{{ $room->id }}') {
            fetch(`{{ route('removeGameRoomGuest', ['room' => $room->id]) }}`, {
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

    //チャット機能
    $(document).ready(function(){
        const roomId = '{{$room->id}}';
        const chatBox = $('#chat-box');
        let lastFetched = null;

        // メッセージを取得する関数
        function fetchMessages() {
            $.ajax({
                url: `/chat/${roomId}/messages`,
                method: 'GET',
                data: {
                    lastFetched: lastFetched
                },
                success: function(messages) {
                    if(messages.length > 0){
                        messages.forEach(function(message){
                            const messageHtml = `
                                <div class="mb-2">
                                    <strong>${escapeHtml(message.user.name)}:</strong> ${escapeHtml(message.message)}
                                    <br>
                                    <small class="text-muted">${formatTimestamp(message.created_at)}</small>
                                </div>
                            `;
                            chatBox.append(messageHtml);
                            lastFetched = message.created_at;
                        });
                        chatBox.scrollTop(chatBox[0].scrollHeight);
                    }
                },
                error: function(xhr){
                    console.error('メッセージの取得に失敗しました。', xhr);
                }
            });
        }

        // メッセージ送信の処理
        $('#message-form').submit(function(e){
            e.preventDefault();
            const message = $('#message').val().trim();
            if(message === '') return;

            $.ajax({
                url: `/chat/${roomId}/messages`,
                method: 'POST',
                data: {
                    message: message,
                    _token: '{{ csrf_token() }}'
                },
                success: function(newMessage){
                    $('#message').val('');
                    const messageHtml = `
                        <div class="mb-2">
                            <strong>${escapeHtml(newMessage.user.name)}:</strong> ${escapeHtml(newMessage.message)}
                            <br>
                            <small class="text-muted">${formatTimestamp(newMessage.created_at)}</small>
                        </div>
                    `;
                    chatBox.append(messageHtml);
                    lastFetched = newMessage.created_at;
                    chatBox.scrollTop(chatBox[0].scrollHeight);
                },
                error: function(xhr){
                    alert('メッセージの送信に失敗しました。');
                }
            });
        });

        // 定期的にメッセージを取得（例：2秒ごと）
        setInterval(fetchMessages, 2000);

        // 初回読み込み
        fetchMessages();

        // セキュリティ: XSS対策としてエスケープ関数を追加
        function escapeHtml(text) {
            return $('<div>').text(text).html();
        }

        // タイムスタンプのフォーマット関数
        function formatTimestamp(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString();
        }
    });
</script>

    <!-- 必要なJavaScriptライブラリの読み込み -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrapなど必要なJSを追加 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- result.jsの読み込み -->
<script src="{{ asset('/js/result.js') }}"></script>

</html>