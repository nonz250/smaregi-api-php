<?php
declare(strict_types=1);

require_once '../../vendor/autoload.php';

use Dotenv\Dotenv;
use Nonz250\SmaregiApiPhp\Foundation\Credential;
use Nonz250\SmaregiApiPhp\Foundation\PsrFactories;
use Nonz250\SmaregiApiPhp\Login\Authorize\AuthorizeRequest;
use Nonz250\SmaregiApiPhp\Login\Client;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientExceptionInterface;

(Dotenv::createImmutable(__DIR__))->load();

$psr17Factory = new Psr17Factory();
$psrFactory = new PsrFactories($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

$client = new Client(
    $psrFactory->getUriFactory()->createUri((string)$_ENV['SMAREGI_IDP_HOST']),
    new Credential(
        (string)$_ENV['SMAREGI_CLIENT_ID'],
        (string)$_ENV['SMAREGI_CLIENT_SECRET'],
    ),
    new \Http\Client\Curl\Client(),
    $psrFactory
);

try {
    $request = (new AuthorizeRequest())
        ->withScopes(['openid', 'email', 'profile', 'offline_access']);
    $response = $client->authorize($request);
    var_dump($response);
    exit();
    //    var_dump($response->getHeader('Location'));
    //    exit();
    //    foreach ($response->getHeaders() as $name => $values) {
    //        foreach ($values as $value) {
    //            header(sprintf('%s: %s', $name, $value), false);
    //        }
    //    }
    //    exit();
} catch (JsonException|ClientExceptionInterface $e) {
}
