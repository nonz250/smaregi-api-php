<?php
declare(strict_types=1);

namespace Tests\Client;

use Faker\Factory;
use Fig\Http\Message\StatusCodeInterface;
use JsonException;
use Nonz250\SmaregiApiPhp\Client\Client;
use Nonz250\SmaregiApiPhp\Client\Token\TokenRequest;
use Nonz250\SmaregiApiPhp\Foundation\Credential;
use Nonz250\SmaregiApiPhp\Foundation\PsrFactories;
use Nonz250\SmaregiApiPhp\Foundation\SmaregiApiHttpException;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

final class ClientTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $psr17Factory = new Psr17Factory();
        $psrFactory = new PsrFactories($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        $this->client = new Client(
            $psrFactory->getUriFactory()->createUri((string)getenv('SMAREGI_IDP_HOST')),
            new Credential(
                (string)getenv('SMAREGI_CLIENT_ID'),
                (string)getenv('SMAREGI_CLIENT_SECRET'),
            ),
            new \Http\Client\Curl\Client(),
            $psrFactory
        );
    }

    /**
     * @throws ClientExceptionInterface
     * @throws JsonException
     */
    public function testAppAccessToken(): void
    {
        $scopes = ['pos.products:read', 'pos.customers:read'];
        $request = (new TokenRequest((string)getenv('SMAREGI_CONTRACT_ID')))
            ->withScopes($scopes);
        $response = $this->client->token($request);
        $this->assertSame(implode(' ', $scopes), $response['scope']);
        $this->assertNotEmpty($response['access_token']);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws JsonException
     */
    public function testHttpException(): void
    {
        $faker = Factory::create();

        $psr17Factory = new Psr17Factory();
        $psrFactory = new PsrFactories($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

        // ContractId is empty.
        try {
            $request = new TokenRequest('');
            $this->client->token($request);
        } catch (SmaregiApiHttpException $e) {
            $response = $e->getResponse();
            $this->assertArrayHasKey('status', $response);
            $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, (int)$response['status']);
        }

        // Scope is empty.
        try {
            $request = (new TokenRequest((string)getenv('SMAREGI_CONTRACT_ID')))
                ->withScopes(['unknown']);
            $this->client->token($request);
        } catch (SmaregiApiHttpException $e) {
            $response = $e->getResponse();
            $this->assertArrayHasKey('status', $response);
            $this->assertSame(StatusCodeInterface::STATUS_BAD_REQUEST, (int)$response['status']);
        }

        // Credential is invalid.
        $client = new Client(
            $psrFactory->getUriFactory()->createUri((string)getenv('SMAREGI_IDP_HOST')),
            new Credential($faker->text, $faker->text),
            new \Http\Client\Curl\Client(),
            $psrFactory
        );

        try {
            $request = new TokenRequest((string)getenv('SMAREGI_CONTRACT_ID'));
            $client->token($request);
        } catch (SmaregiApiHttpException $e) {
            $response = $e->getResponse();
            $this->assertArrayHasKey('status', $response);
            $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, (int)$response['status']);
        }
    }
}
