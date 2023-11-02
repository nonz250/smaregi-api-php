<?php
declare(strict_types=1);

require_once '../../vendor/autoload.php';

use Dotenv\Dotenv;
use Nonz250\SmaregiApiPhp\Auth\SmaregiProvider;

session_start();

(Dotenv::createImmutable(__DIR__))->load();

try {
    $error = $_GET['error'] ?? '';

    if ($error) {
        throw new RuntimeException(implode('<br>', [
            'error: ' . ($_GET['error'] ?? ''),
            'error_description: ' . ($_GET['error_description'] ?? ''),
            'hint: ' . ($_GET['hint'] ?? ''),
            'message: ' . ($_GET['message'] ?? ''),
        ]));
    }
} catch (Throwable $e) {
    echo htmlspecialchars($e->getMessage());
    exit();
}

try {
    $sessionState = (string)($_SESSION['sampleState'] ?? '');

    if ($sessionState === '') {
        throw new RuntimeException('Session state is empty.');
    }
    $sampleState = $_GET['state'] ?? '';

    if ($sessionState !== $sampleState) {
        throw new RuntimeException('Invalid state.');
    }
} catch (Throwable $e) {
    echo htmlspecialchars($e->getMessage());
    exit();
}

try {
    $provider = new SmaregiProvider(
        (string)$_ENV['SMAREGI_IDP_HOST'],
        (string)$_ENV['SMAREGI_CLIENT_ID'],
        (string)$_ENV['SMAREGI_CLIENT_SECRET'],
        'http://localhost/callback.php',
    );

    $code = $_GET['code'] ?? '';

    $provider->setPkceCode($_SESSION['samplePkceCode'] ?? '');
    $accessToken = $provider->getAccessToken('authorization_code', [
        'code' => $code,
    ]);

    $resourceOwner = $provider->getResourceOwner($accessToken);

    echo htmlspecialchars(var_export($resourceOwner->toArray(), true));

    $accessToken = $provider->getAccessToken('refresh_token', [
        'refresh_token' => $accessToken->getRefreshToken(),
    ]);
} catch (Throwable $e) {
    echo htmlspecialchars($e->getMessage());
    exit();
}
