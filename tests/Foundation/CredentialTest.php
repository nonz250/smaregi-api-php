<?php
declare(strict_types=1);

namespace Tests\Foundation;

use Faker\Factory;
use Nonz250\SmaregiApiPhp\Foundation\Credential;
use PHPUnit\Framework\TestCase;

final class CredentialTest extends TestCase
{
    public function test__construct(): void
    {
        $faker = Factory::create();
        $clientId = $faker->text(32);
        $clientSecret = $faker->text(64);
        $credential = new Credential($clientId, $clientSecret);
        $this->assertSame('Basic ' . base64_encode("$clientId:$clientSecret"), $credential->authorizationBasic());
        $this->assertSame($clientId, $credential->clientId());
    }
}
