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
        <form id="searchForm">
            @csrf
            <h1>部屋番号を入力して下さい</h1>
            <input type="number" name="roomid" id="roomid" required>
            <button type="submit" class="btn btn-primary">検索</button>
            <!-- 部屋番号(=roomid)を入力してその部屋に参加させたい -->
        </form>

    <script>        
    document.getElementById('searchForm').addEventListener('submit', function(event) {
        event.preventDefault(); // フォームのデフォルト動作を防ぐ

        var roomId = document.getElementById('roomid').value;

        fetch('{{ route("search") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ roomid: roomId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists && !data.isFull) {
                if (confirm('部屋が見つかりました。参加しますか？')) {
                    // 参加ボタンをクリックした場合、リダイレクト
                    window.location.href = //移動先のアドレス
                }
            } else {
                alert('この部屋は存在しない、あるいは満員のため参加できません');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
    </script>
</body>
</html>