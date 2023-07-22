<?php
declare(strict_types=1);

namespace Tests;

use Dotenv\Dotenv;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (Dotenv::createImmutable(__DIR__))->load();

        if (empty($_ENV['SMAREGI_IDP_HOST'])
            || empty($_ENV['SMAREGI_CLIENT_ID'])
            || empty($_ENV['SMAREGI_CLIENT_SECRET'])
            || empty($_ENV['SMAREGI_CONTRACT_ID'])
        ) {
            $this->markTestIncomplete('クレデンシャル情報が存在しません。');
        }
    }
}
