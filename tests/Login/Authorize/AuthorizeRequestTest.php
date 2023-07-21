<?php
declare(strict_types=1);

namespace Tests\Login\Authorize;

use Exception;
use Faker\Factory;
use Nonz250\SmaregiApiPhp\Login\Authorize\AuthorizeRequest;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class AuthorizeRequestTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test__construct(): void
    {
        $faker = Factory::create();
        $clientId = $faker->word;
        $scope1 = $faker->word;
        $scope2 = $faker->word;
        $uri = $faker->url;

        $request = (new AuthorizeRequest())
            ->withClientId($clientId)
            ->withScopes([
                $scope1,
                $scope2,
            ]);

        $ref = new ReflectionClass($request::class);
        $params = $ref->getProperty('params')->getValue($request);

        $this->assertArrayHasKey('response_type', $params);
        $this->assertSame('code', $params['response_type']);

        $this->assertArrayHasKey('client_id', $params);
        $this->assertSame($clientId, $params['client_id']);

        $this->assertArrayHasKey('scope', $params);
        $this->assertSame("$scope1 $scope2", $params['scope']);

        $this->assertArrayHasKey('state', $params);
        $this->assertNotEmpty($params['client_id']);

        $this->assertArrayNotHasKey('redirect_uri', $params);

        $this->assertArrayHasKey('code_challenge', $params);
        $this->assertNotEmpty($params['code_challenge']);

        $this->assertArrayHasKey('code_challenge_method', $params);
        $this->assertSame('S256', $params['code_challenge_method']);

        // redirect uri.
        $request = (new AuthorizeRequest())
            ->withClientId($clientId)
            ->withRedirectUri($uri)
            ->withScopes([
                $scope1,
                $scope2,
            ]);

        $ref = new ReflectionClass($request::class);
        $params = $ref->getProperty('params')->getValue($request);

        $this->assertArrayHasKey('response_type', $params);
        $this->assertSame('code', $params['response_type']);

        $this->assertArrayHasKey('client_id', $params);
        $this->assertSame($clientId, $params['client_id']);

        $this->assertArrayHasKey('scope', $params);
        $this->assertSame("$scope1 $scope2", $params['scope']);

        $this->assertArrayHasKey('state', $params);
        $this->assertNotEmpty($params['client_id']);

        $this->assertArrayHasKey('redirect_uri', $params);
        $this->assertSame($uri, $params['redirect_uri']);

        $this->assertArrayHasKey('code_challenge', $params);
        $this->assertNotEmpty($params['code_challenge']);

        $this->assertArrayHasKey('code_challenge_method', $params);
        $this->assertSame('S256', $params['code_challenge_method']);
    }
}
