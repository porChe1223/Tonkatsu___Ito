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
        <a href="{{ url('/dashboard') }}"><button type="submit" class="go-result-button">ダッシュボードに戻る</button></a>
        <table>
        <thead>
            <tr>
                <th>名前</th>
                <th>カード番号</th>
            </tr>
        </thead>
        <tbody>
            <!-- 昇順に修正する -->
            @foreach($participants as $participant)
                <tr class="card-number">
                    <td>{{ $participant->name }}</td>
                    <td>{{ $participant->card_number }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body> 

</html>