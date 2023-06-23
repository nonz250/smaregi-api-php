# smaregi-api-php

* [PSR-7: HTTP message interfaces - PHP-FIG](https://www.php-fig.org/psr/psr-7/)
* [PSR-17: HTTP Factories - PHP-FIG](https://www.php-fig.org/psr/psr-17/)
* [PSR-18: HTTP Client - PHP-FIG](https://www.php-fig.org/psr/psr-18/)

上記 PSR にて決められたインターフェースを実装したスマレジ・プラットフォーム API クライアントライブラリです。

## Development

このリポジトリは Docker の利用を推奨しています。
詳細は Makefile を参照してください。

### Help

```shell
make help
```

### Build

```shell
make build
```

### Start development server

```shell
make up
```

### Fixer command.

```shell
make lint
```

### Test command.

```shell
docker compose run --rm app ./vendor/bin/phpunit
```
