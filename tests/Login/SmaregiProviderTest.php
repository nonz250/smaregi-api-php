<?php
declare(strict_types=1);

namespace Tests\Login;

use Faker\Factory;
use Nonz250\SmaregiApiPhp\Auth\SmaregiProvider;
use Tests\TestCase;

final class SmaregiProviderTest extends TestCase
{
    private SmaregiProvider $smaregiProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->smaregiProvider = new SmaregiProvider(
            (string)$_ENV['SMAREGI_IDP_HOST'],
            (string)$_ENV['SMAREGI_CLIENT_ID'],
            (string)$_ENV['SMAREGI_CLIENT_SECRET']
        );
    }

    public function testLogin(): void
    {
        $faker = Factory::create();
        $contractId = $faker->text;
        $authorizationUrl = $this->smaregiProvider->getAuthorizationUrl();
        $this->assertNotEmpty($authorizationUrl);
        $this->assertNotEmpty($this->smaregiProvider->getState());
        $this->assertNotEmpty($this->smaregiProvider->getPkceCode());
        $this->assertStringContainsString('/authorize/token', $this->smaregiProvider->getBaseAccessTokenUrl([]));
        $this->assertStringContainsString('/app/' . $contractId . '/token', $this->smaregiProvider->getBaseAccessTokenUrl([
            'grant_type' => 'client_credentials',
            'contract_id' => $contractId,
        ]));
    }
}
