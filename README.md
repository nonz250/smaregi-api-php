# smaregi-api-php

## Development

このリポジトリは Docker の利用を推奨しています。
詳細は Makefile を参照してください。

### Build

```shell
make build
```

### Fixer Command.

```shell
make lint
```

### Test Command.

```shell
docker compose run --rm app ./vendor/bin/phpunit
```