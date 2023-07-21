<?php
declare(strict_types=1);

namespace Tests\Login;

use Fig\Http\Message\StatusCodeInterface;
use JsonException;
use Nonz250\SmaregiApiPhp\Foundation\Credential;
use Nonz250\SmaregiApiPhp\Foundation\PsrFactories;
use Nonz250\SmaregiApiPhp\Login\Authorize\AuthorizeRequest;
use Nonz250\SmaregiApiPhp\Login\Client;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientExceptionInterface;
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

    /**
     * @throws ClientExceptionInterface
     * @throws JsonException
     */
    public function testLogin(): void
    {
        $request = (new AuthorizeRequest());
        $response = $this->client->authorize($request);
        $this->assertSame(StatusCodeInterface::STATUS_FOUND, $response->getStatusCode());
    }
}
