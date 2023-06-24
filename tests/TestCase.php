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
    }
}
