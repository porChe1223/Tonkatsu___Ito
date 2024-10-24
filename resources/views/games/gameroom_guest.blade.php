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

            <div>
                <p>自分で考えたお題で遊ぶ！</p>
                <form action="{{ route('MakeThemeInGame', ['room' => $room->id]) }}" method="POST">
                    @csrf
                    <input type="text" id="ThemeIdea" name="ThemeIdea">
                    <button type="submit" class="btn btn-primary">お題を変更</button>
                </form>
            </div>

            <div id="card_number-container">
                <div class="instructions">あなたのカード番号</div>
                <span id="card-number">
                    {{$user->card_number}}
                </span>
            </div>
        </div>
        <!-- 以下はチャットルーム -->
        <!-- <div id="chat-container">
                <div id="chat-left">
                    <div class="chat-text">
                        左側のチャットメッセージ
                    </div>
                </div>
                <div class="chat-clear"></div>
                <div id="chat-right">
                    <div class="chat-text">
                        右側のチャットメッセージ
                    </div>
                </div>
                <div class="chat-clear"></div>
                <div id="chat-send-container">
                    <input id="chat-message-text" type="text"></input>
                    <button id="chat-message-send-button">送信</button>
                </div>
            </div> -->
        </form>
    </div>


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
            }, 2000); // 1秒ごとに実行
        });
    </script>
</body>

</html>