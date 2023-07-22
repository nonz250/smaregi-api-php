# smaregi-api-php

* [PSR-7: HTTP message interfaces - PHP-FIG](https://www.php-fig.org/psr/psr-7/)
* [PSR-17: HTTP Factories - PHP-FIG](https://www.php-fig.org/psr/psr-17/)
* [PSR-18: HTTP Client - PHP-FIG](https://www.php-fig.org/psr/psr-18/)

上記 PSR にて決められたインターフェースで実装したスマレジ・プラットフォーム API クライアントライブラリです。

## Usage

* [仕様書 | ドキュメント | スマレジ・デベロッパーズ](https://developers.smaregi.dev/documents/api_reference)
* [スマレジ・プラットフォームAPI 共通仕様書](https://developers.smaregi.dev/apidoc/common/)
* [スマレジ・プラットフォームAPI POS仕様書](https://www1.smaregi.dev/apidoc/)
* [スマレジ・プラットフォームAPI 受注管理 仕様書](https://order-shipment.smaregi.dev/apidoc/)
* [スマレジ・プラットフォームAPI Waiter 仕様書](https://waiter1.smaregi.dev/apidoc/)
* [スマレジ・プラットフォームAPI Timecard仕様書](https://timecard1.smaregi.dev/apidoc/)

### アプリアクセストークン取得

https://github.com/nonz250/smaregi-api-php/blob/main/sample/public/application_token.php

`getAccessToken` の引数に `new SmaregiClientCredentials()` を渡し、 `options` に `contract_id` を指定してください。
また、その際に必要に応じて `scope` を指定してください。

```php
<?php
declare(strict_types=1);

use Nonz250\SmaregiApiPhp\Auth\SmaregiClientCredentials;
use Nonz250\SmaregiApiPhp\Auth\SmaregiProvider;

$provider = new SmaregiProvider(
    'SMAREGI_IDP_HOST',
    'SMAREGI_CLIENT_ID',
    'SMAREGI_CLIENT_SECRET',
);

$accessToken = $provider->getAccessToken(new SmaregiClientCredentials(), [
    'contract_id' => 'SMAREGI_CONTRACT_ID',
    'scope' => ['pos.products:read', 'pos.customers:read'],
]);
```

### ユーザーアクセストークン

https://github.com/nonz250/smaregi-api-php/blob/main/sample/public/auth.php

必要に応じて `redirect_uri` を指定してください。
同様に `getAuthorizationUrl` 呼び出し時に `scope` に必要なものを指定してください。

セキュリティ対策のため、 `state` と `pkce` を保持し、 `redirect_uri` の先でチェック処理をしてください。

```php
<?php
declare(strict_types=1);

use Nonz250\SmaregiApiPhp\Auth\SmaregiProvider;

session_start();

$provider = new SmaregiProvider(
    'SMAREGI_IDP_HOST,
    'SMAREGI_CLIENT_ID',
    'SMAREGI_CLIENT_SECRET',
    'http://localhost/callback.php',
);

$authorizationUrl = $provider->getAuthorizationUrl([
    'scope' => ['openid', 'email', 'profile', 'offline_access', 'pos.products:read', 'pos.customers:read'],
]);
$_SESSION['sampleState'] = $provider->getState();
$_SESSION['samplePkceCode'] = $provider->getPkceCode();
header('Location: ' . $authorizationUrl);
exit();
```

https://github.com/nonz250/smaregi-api-php/blob/main/sample/public/callback.php

セキュリティ対策のため、保持していた `state` と `pkce` のチェック処理をしてください。

```php
<?php
declare(strict_types=1);

use Nonz250\SmaregiApiPhp\Auth\SmaregiProvider;

session_start();

$sessionState = (string)($_SESSION['sampleState'] ?? '');

if ($sessionState === '') {
    throw new RuntimeException('Session state is empty.');
}
$sampleState = $_GET['state'] ?? '';

if ($sessionState !== $sampleState) {
    throw new RuntimeException('Invalid state.');
}

$provider = new SmaregiProvider(
    'SMAREGI_IDP_HOST',
    'SMAREGI_CLIENT_ID',
    'SMAREGI_CLIENT_SECRET',
    'http://localhost/callback.php',
);

$code = $_GET['code'] ?? '';

$provider->setPkceCode($_SESSION['samplePkceCode'] ?? '');
$accessToken = $provider->getAccessToken('authorization_code', [
    'code' => $code,
]);

$resourceOwner = $provider->getResourceOwner($accessToken);

$accessToken = $provider->getAccessToken('refresh_token', [
    'refresh_token' => $accessToken->getRefreshToken(),
]);
```

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

https://github.com/nonz250/smaregi-api-php/tree/main/sample

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
