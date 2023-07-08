<?php
declare(strict_types=1);

namespace Tests\Foundation;

use Exception;
use Nonz250\SmaregiApiPhp\Foundation\PkceUtil;
use PHPUnit\Framework\TestCase;

final class PkceUtilTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCodeVerifier(): void
    {
        $pkce = new PkceUtil();
        $actual = $pkce->codeVerifier();
        $this->assertTrue(43 <= mb_strlen($actual) && mb_strlen($actual) <= 128);
    }

    /**
     * @throws Exception
     */
    public function testCodeChallenge(): void
    {
        $pkce = new PkceUtil();
        $codeVerifier = $pkce->codeVerifier();
        $expected = str_replace('=', '', strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'));
        $actual = $pkce->codeChallenge();
        $this->assertSame($expected, $actual);
    }
}
