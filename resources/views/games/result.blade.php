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
        <p>順番が間違っています。もう一度挑戦してください。</p>
        @endif


        <h2>あなたが入力した順番</h2>
        <ul>
            @foreach ($player_order as $name)
            <li>{{ $name }}</li>
            @endforeach
        </ul>

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
        <form action="{{ route('destroyRoom', $room->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">ゲームを終了</button>
        </form>
</body>

</html>