# smaregi-api-php

* [PSR-7: HTTP message interfaces - PHP-FIG](https://www.php-fig.org/psr/psr-7/)
* [PSR-17: HTTP Factories - PHP-FIG](https://www.php-fig.org/psr/psr-17/)
* [PSR-18: HTTP Client - PHP-FIG](https://www.php-fig.org/psr/psr-18/)

上記 PSR にて決められたインターフェースを実装したスマレジ・プラットフォーム API クライアントライブラリです。

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

### Please execute before make Pull Request.

Pull Request を作成する前には `make pr` を実行してください。

* formatter
* static analyzer
* unit test

が実行されます。

```shell
make pr
```
