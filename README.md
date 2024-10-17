# 環境構築手順

-   git clone
-   git switch develop
-   git pull origin develop
-   mkdir -p storage/framework/cache/data/
-   mkdir -p storage/framework/app/cache
-   mkdir -p storage/framework/sessions
-   mkdir -p storage/framework/views
-   docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
-   cp .env.example .env
-   env ファイルの中身(APP_KEY と DB)を書き加えて！
    APP_KEY と DB の内容は Slack に載せてます！
-   ./vendor/bin/sail up
-   ./vendor/bin/sail php artisan migrate
-   ./vendor/bin/sail npm install
-   ./vendor/bin/sail npm run build
-   ./vendor/bin/sail php artisan db:seed --class=UserSeeder
-   ./vendor/bin/sail php artisan db:seed --class=ThemeSeeder

#　開発の手順

-   git switch develop
-   git pull origin develop
-   git switch -c '新しいブランチ名'　 ← 　命名ルールは feat/~
    すでに作ってるブランチに遷移するときは
    git switch '自分のブランチ名'
    --作成を開始する--
-   git add '変更したファイル名'
-   git commit -m '何をしたか詳細にコメント'
-   git push origin '新しいブランチ名'
-   gitHub 上の pull request から develop ← '新しいブランチ名'にプルリクエストを送る
    最後李が確認して結合します！

## 作業が終わったらドッカーは閉じて！

./vendor/bin/sail down
