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
            @foreach($usersWithCards as $user)
                <tr class="card-number">
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->card_number }}</td>
                </tr>
            @endforeach
        </tdoby>
    </table>
</body> 

</html>