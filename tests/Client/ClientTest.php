<?php
declare(strict_types=1);

namespace Tests\Client;

use Nonz250\SmaregiApiPhp\Client\Client;
use Nonz250\SmaregiApiPhp\Foundation\Credential;
use Nonz250\SmaregiApiPhp\Foundation\PsrFactories;
use Nyholm\Psr7\Factory\Psr17Factory;
use Tests\TestCase;

final class ClientTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $psr17Factory = new Psr17Factory();
        $psrFactory = new PsrFactories($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        $this->client = new Client(
            $psrFactory->getUriFactory()->createUri((string)$_ENV['SMAREGI_IDP_HOST']),
            new Credential(
                (string)$_ENV['SMAREGI_CLIENT_ID'],
                (string)$_ENV['SMAREGI_CLIENT_SECRET'],
            ),
            new \Http\Client\Curl\Client(),
            $psrFactory
        );
    }

    public function testHttpException(): void
    {
        $this->markTestIncomplete('TODO: Write http exception test.');
    }
}
