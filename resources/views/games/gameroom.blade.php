<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>GameRoom</title>
        <link rel="stylesheet" href="{{ asset('/css/gameroom.blade.css')}}">
    </head>
    <body>
        <div id="all-container">
            <h1>GameRoom</h1>
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
            <div id="chat-room">
                チャットルーム作成する
            </div>
        </div>

        <div>

        </div>
    </body>

</html>

<script>

</script>
