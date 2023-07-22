# smaregi-api-php

* [PSR-7: HTTP message interfaces - PHP-FIG](https://www.php-fig.org/psr/psr-7/)
* [PSR-17: HTTP Factories - PHP-FIG](https://www.php-fig.org/psr/psr-17/)
* [PSR-18: HTTP Client - PHP-FIG](https://www.php-fig.org/psr/psr-18/)

上記 PSR にて決められたインターフェースで実装したスマレジ・プラットフォーム API クライアントライブラリです。

## Contributing

このリポジトリは Docker の利用を推奨しています。

詳細は Makefile を参照してください。

### Help

各コマンドのヘルプが表示されます。

```shell
make help
```

### Build

Docker における開発環境をビルドします。

```shell
make build
```

`/tests/.env.example` 及び `sample/public/.env.example` をそれぞれ `.env` としてコピーし、自分のスマレジ契約のクレデンシャル情報に置き換えてください。
スマレジ契約のクレデンシャル情報は [スマレジ・デベロッパーズ](https://developers.smaregi.dev/) に登録し、アプリを作成することで取得してください。
この際、パブリックアプリかプライベートアプリかは問いません。

|変数名|内容|
|-----|---|
|SMAREGI_IDP_HOST|スマレジIdPサーバーのホスト。デフォルトはサンドボックス環境のホスト。|
|SMAREGI_CONTRACT_ID|対象の契約ID|
|SMAREGI_CLIENT_ID|対象のクライアントID|
|SMAREGI_CLIENT_SECRET|対象のクライアントシークレット|

### Sample

```shell
make sample
```

上記を実行後、 http://localhost へアクセス。

### Please execute before make Pull Request.

Pull Request を作成する前には `make pr` を実行してください。

* formatter
* static analyzer
* unit test

が実行されます。

```shell
make pr
```
