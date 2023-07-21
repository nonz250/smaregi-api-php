<?php
declare(strict_types=1);

require_once '../../vendor/autoload.php';

use Dotenv\Dotenv;
use Nonz250\SmaregiApiPhp\Login\SmaregiProvider;

session_start();

(Dotenv::createImmutable(__DIR__))->load();

$provider = new SmaregiProvider(
    (string)$_ENV['SMAREGI_IDP_HOST'],
    (string)$_ENV['SMAREGI_CLIENT_ID'],
    (string)$_ENV['SMAREGI_CLIENT_SECRET'],
    'http://localhost/callback.php',
);

if (headers_sent()) {
    throw new RuntimeException('Headers were already sent.');
}
$authorizationUrl = $provider->getAuthorizationUrl();
$_SESSION['sampleState'] = $provider->getState();
$_SESSION['samplePkceCode'] = $provider->getPkceCode();
header('Location: ' . $authorizationUrl);
exit();
