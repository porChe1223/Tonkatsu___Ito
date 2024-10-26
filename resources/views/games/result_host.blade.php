<!DOCTYPE html>
<html lang="Ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>結果画面</title>
    <link rel="stylesheet" href="{{ asset('css/result.blade.css') }}">
</head>

<body>
    <div>
        <h1>答え</h1>

        @if($isCorrect)
        <p>順番は正しいです！おめでとうございます！</p>
        @else
        <p>残念！順番が間違っています。</p>
        @endif

        <table>
            <thead>
                <tr>
                    <th>名前</th>
                    <th>カード番号</th>
                </tr>
            </thead>
            <tbody>
                @foreach($participants as $participant)
                <tr class="card-number">
                    <td>{{ $participant->name }}</td>
                    <td>{{ $participant->card_number }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h2>あなたが入力した順番</h2>
        <ul>
            @foreach ($player_order as $name)
            <li>{{ $name }}</li>
            @endforeach
        </ul>

        <form action="{{ route('removeRoomHost', $room->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">ゲームを終了</button>
        </form>
</body>

<script>
let isAutoRedirect = false;

window.addEventListener('beforeunload', (event) => {
    if (!isAutoRedirect && window.location.href !== '/home/{{ $room->id }}') {
        fetch(`{{ route('removeGameRoomHost', ['room' => $room->id]) }}`, {
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
</script>

</html>