<?php
declare(strict_types=1);

namespace Tests\Auth;

use Faker\Factory;
use Nonz250\SmaregiApiPhp\Auth\SmaregiClientCredentials;
use PHPUnit\Framework\TestCase;

final class SmaregiClientCredentialsTest extends TestCase
{
    public function testPrepareRequestParameters(): void
    {
        $faker = Factory::create();
        $expected = [$faker->text, $faker->text];
        $contractId = $faker->text;
        $smaregiClientCredentials = new SmaregiClientCredentials();
        $actual = $smaregiClientCredentials->prepareRequestParameters([], [
            'contract_id' => $contractId,
            'scope' => $expected,
        ]);
        $this->assertArrayHasKey('grant_type', $actual);
        $this->assertArrayHasKey('contract_id', $actual);
        $this->assertArrayHasKey('scope', $actual);
        $this->assertSame(implode(' ', $expected), $actual['scope']);
    }
}
