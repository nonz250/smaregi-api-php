<?php
declare(strict_types=1);

namespace Tests\Login;

use Nonz250\SmaregiApiPhp\Login\SmaregiProvider;
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
        $this->assertNotEmpty($this->smaregiProvider->getState());
        $this->assertNotEmpty($this->smaregiProvider->getPkceCode());
        $authorizationUrl = $this->smaregiProvider->getAuthorizationUrl();
        $this->assertNotEmpty($authorizationUrl);
    }
}
