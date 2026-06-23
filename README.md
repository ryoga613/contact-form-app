## プロジェクト名
    COACHTECH お問い合わせフォーム



 ## 概要

    一般ユーザーと管理者の双方にとって使いやすく、実務を意識したセキュアなお問い合わせ・管理システムを実装しました。

    『　一般ユーザー向け機能　』

        お問い合わせフォーム機能
            ー　誰でもサイト上から直接お問い合わせが可能
        入力バリデーション（エラーチェック）機能
            ー　未入力での送信や、メールアドレスの形式エラー（`@` がない等）を自動で検知し、ユーザーに分かりやすく警告を表示。
        送信完了画面（サンクスページ）の表示**
            ー　送信が成功したことをユーザーへ明示し、二重送信を防止。

    『　管理者向け機能（管理画面）　』

        管理者専用ページ（認証機能）
            ー　認証された管理者のみがアクセスできるセキュアな管理エリア。
        お問い合わせ管理（CRUD機能の完全実装）
            ー　一覧表示：届いたお問い合わせを時系列で確認できるリスト（件数が増えても見やすいようにページネーションを導入）。
            ー　詳細確認：お問い合わせ内容の本文や、投稿者の詳細情報を個別に確認。 
            ー　編集機能：対応状況（例：「未対応」「対応中」「完了」など）のステータス更新。
            ー　削除機能：スパムや対応が終わった不要なデータをデータベースから削除。
        操作後のフラッシュメッセージ表示
            ー　編集や削除が完了した際、画面上部に「更新しました」「削除しました」と通知を出すことで、管理者の操作ミスを防止。



##　ER図

    <img width="1822" height="1932" alt="laravel" src="https://github.com/user-attachments/assets/a4509d34-4dee-496c-9040-607ac01d12ca" />



##　開発環境のセットアップ

    ** 1. Laravelプロジェクトの作成 (Laravel 10.x) **

        注意: curl -s "https://laravel.build/..." は最新版のLaravelをインストールするため、今回は使用しません。

        以下のDockerコマンドを実行して、Laravel 10.xを明示的に指定してプロジェクトを作成します。

    # Laravel 10.x を指定してプロジェクトを作成
        docker run --rm \
            -u "$(id -u):$(id -g)" \
            -v "$(pwd):/var/www/html" \
            -w /var/www/html \
            -e COMPOSER_CACHE_DIR=/tmp/composer_cache \
            laravelsail/php82-composer:latest \
            composer create-project laravel/laravel:^10.0 contact-form-app

    ** 2. Laravel Sailのインストール **

        プロジェクト作成後、contact-form-app ディレクトリに移動し、Laravel Sailをインストールします。

            # プロジェクトディレクトリに移動
            cd contact-form-app

            # Laravel Sailをインストール
            docker run --rm \
                -u "$(id -u):$(id -g)" \
                -v "$(pwd):/var/www/html" \
                -w /var/www/html \
                -e COMPOSER_CACHE_DIR=/tmp/composer_cache \
                laravelsail/php82-composer:latest \
                composer require laravel/sail --dev

            # Sailの設定ファイルをパブリッシュ（MySQLを選択）
            docker run --rm \
                -u "$(id -u):$(id -g)" \
                -v "$(pwd):/var/www/html" \
                -w /var/www/html \
                -e COMPOSER_CACHE_DIR=/tmp/composer_cache \
                laravelsail/php82-composer:latest \
                php artisan sail:install --with=mysql

            # ※M1/M2/M3 Mac（Apple Silicon）をお使いの方

            Apple Silicon搭載のMacでは、`sail up -d`実行時に以下のエラーが発生することがあります：

            ```
            no matching manifest for linux/arm64/v8
            ```

            解決方法: `compose.yaml`を開き、mysqlサービスに`platform: 'linux/amd64'`を追加してください。
            mysql:
                image: 'mysql/mysql-server:8.0'
                platform: 'linux/amd64'  # ← この行を追加
                ports:

    ** 3. .env ファイルの設定 **

        .env ファイルを開き、データベース接続情報が以下と一致していることを確認します。

        DB_CONNECTION=mysql
        DB_HOST=mysql
        DB_PORT=3306
        DB_DATABASE=laravel
        DB_USERNAME=sail
        DB_PASSWORD=password

        重要: DB_HOST は localhost や 127.0.0.1 ではなく、Dockerコンテナ名である mysql を指定します。

    ** 4. フロントエンドのセットアップ (Vite & Tailwind CSS) **

        本プロジェクトでは、フロントエンドのスタイリングにTailwind CSSを使用します。

        1. NPM依存パッケージのインストール
        > 重要: sail npm install を実行する前に、必ずSailコンテナが起動していることを確認してください。
        sail npm install

        2. Tailwind CSSのインストール
        sail npm install -D tailwindcss@^3.4.0 postcss autoprefixer
        sail npm install alpinejs

        3. 設定ファイルの生成
        sail npx tailwindcss init -p

        4. Tailwind CSSのテンプレートパス設定
        tailwind.config.js を開き、以下のように設定します。
        /** @type {import("tailwindcss").Config} */
        export default {
        content: [
            "./resources/**/*.blade.php",
            "./resources/**/*.js",
            "./resources/**/*.vue",
        ],
        theme: {
            extend: {},
        },
        plugins: [],
        }

        5. 提供リポジトリのresourcesディレクトリと入れ替え
        以下のリポジトリをクローンし、resourcesディレクトリを丸ごと入れ替えます。
        git clone https://github.com/coachtech-prepared-file/Preparedblade-ConfirmationTest-ContactForm.git

        入れ替え手順:
        ① Finderでプロジェクトフォルダを開きます。
        open .
        ② プロジェクト内の resources フォルダを削除します。
        ③ クローンしたリポジトリ内の resources フォルダをプロジェクト直下にコピーします。

        ※コマンド操作に慣れている場合は rm -rf と cp -r でも可能ですが、誤削除を防ぐためFinderでの操作を推奨します。

        6. Vite開発サーバーの起動
        sail npm run dev
        注意: sail npm run dev は実行したままにしておく必要があります。

    ** 5. phpMyAdminの追加 **

        compose.yaml を開き、mysql サービスの後に以下の設定を追加してください。

            compose.yaml に追加する内容:

                phpmyadmin:
                    image: 'phpmyadmin:latest'
                    ports:
                        - '${FORWARD_PHPMYADMIN_PORT:-8080}:80'
                    environment:
                        PMA_HOST: mysql
                        PMA_USER: '${DB_USERNAME}'
                        PMA_PASSWORD: '${DB_PASSWORD}'
                    networks:
                        - sail
                    depends_on:
                        - mysql

    ** 6. Sailの起動とエイリアス設定 **

        # Sailをバックグラウンドで起動
        ./vendor/bin/sail up -d

        # エイリアスを設定して 'sail' だけでコマンドを実行できるようにする
        echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >> ~/.zshrc

        # または bash の場合
        # echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >> ~/.bashrc

        # シェルを再起動するか、新しいターミナルを開いてエイリアスを有効にする
        exec $SHELL

    ** 7. アプリケーションキーの生成 **

        ルートで以下のコマンドを実行する
        sail artisan key:generate

    ** 8. データベースのマイグレーションと初期データ投入 **

        sail artisan migrate --seed

        ※既存のデータベースをリセットしたい場合は以下を実行してください。
        sail artisan migrate:fresh --seed

        ⚠️  日本語化／翻訳について:
        — 日本語化は FormRequest の `messages()` と `lang/ja`（認証系）で行います。
        `laravel-lang/*` 系の外部翻訳パッケージ（`composer require laravel-lang/...`）は導入しないでください。
        同系パッケージは 2026年5月のサプライチェーン攻撃でマルウェア配布に悪用された経緯があり、本課題では不要です。



## APIエンドポイント一覧



## 開発環境URL

    http://localhost



##　作成者

     上田　凌雅