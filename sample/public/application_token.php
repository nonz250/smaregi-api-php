<?php
declare(strict_types=1);

require_once '../../vendor/autoload.php';

use Dotenv\Dotenv;
use Nonz250\SmaregiApiPhp\Auth\SmaregiClientCredentials;
use Nonz250\SmaregiApiPhp\Auth\SmaregiProvider;

(Dotenv::createImmutable(__DIR__))->load();

try {
    $provider = new SmaregiProvider(
        (string)$_ENV['SMAREGI_IDP_HOST'],
        (string)$_ENV['SMAREGI_CLIENT_ID'],
        (string)$_ENV['SMAREGI_CLIENT_SECRET'],
    );

    $accessToken = $provider->getAccessToken(new SmaregiClientCredentials(), [
        'contract_id' => (string)$_ENV['SMAREGI_CONTRACT_ID'],
        'scope' => ['pos.products:read', 'pos.customers:read'],
    ]);

    echo $accessToken->getToken();
} catch (Throwable $e) {
    echo $e->getMessage();
    exit();
}
