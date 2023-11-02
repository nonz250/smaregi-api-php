<?php
declare(strict_types=1);

namespace Tests\Auth;

use Faker\Factory;
use League\OAuth2\Client\Provider\AbstractProvider;
use Mockery;
use Nonz250\SmaregiApiPhp\Auth\SmaregiProvider;
use PHPUnit\Framework\TestCase;

final class SmaregiProviderTest extends TestCase
{
    private Mockery\MockInterface $smaregiProviderStub;

    protected function setUp(): void
    {
        parent::setUp();
        $this->smaregiProviderStub = Mockery::mock(
            AbstractProvider::class,
            new SmaregiProvider('', '', ''),
        );
    }

    public function testLogin(): void
    {
        $faker = Factory::create();

        $this->smaregiProviderStub
            ->shouldReceive('getAuthorizationUrl')
            ->andReturn($faker->url);
        $this->smaregiProviderStub
            ->shouldReceive('getState')
            ->andReturn($faker->text);
        $this->smaregiProviderStub
            ->shouldReceive('getPkceCode')
            ->andReturn($faker->text);

        $contractId = $faker->text;
        $authorizationUrl = $this->smaregiProviderStub->getAuthorizationUrl();
        $this->assertNotEmpty($authorizationUrl);
        $this->assertNotEmpty($this->smaregiProviderStub->getState());
        $this->assertNotEmpty($this->smaregiProviderStub->getPkceCode());
        $this->assertStringContainsString('/authorize', $this->smaregiProviderStub->getBaseAuthorizationUrl());
        $this->assertStringContainsString('/authorize/token', $this->smaregiProviderStub->getBaseAccessTokenUrl([]));
        $this->assertStringContainsString('/app/' . $contractId . '/token', $this->smaregiProviderStub->getBaseAccessTokenUrl([
            'grant_type' => 'client_credentials',
            'contract_id' => $contractId,
        ]));
    }
}
