<?php
declare(strict_types=1);

namespace Tests\Client\Token;

use Faker\Factory;
use Nonz250\SmaregiApiPhp\Client\Token\TokenRequest;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

final class TokenRequestTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function test__construct(): void
    {
        $faker = Factory::create();
        $scope1 = $faker->word;
        $scope2 = $faker->word;

        $request = (new TokenRequest($faker->text))
            ->withScopes([
                $scope1,
                $scope2,
            ]);

        $ref = new ReflectionClass($request::class);
        $payload = $ref->getProperty('payload')->getValue($request);

        $this->assertArrayHasKey('grant_type', $payload);
        $this->assertSame('client_credentials', $payload['grant_type']);

        $this->assertArrayHasKey('scope', $payload);
        $this->assertSame("$scope1 $scope2", $payload['scope']);
    }
}
