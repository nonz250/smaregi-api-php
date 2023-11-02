<?php
declare(strict_types=1);

namespace Tests\Foundation;

use Exception;
use Nonz250\SmaregiApiPhp\Foundation\StringUtil;
use PHPUnit\Framework\TestCase;

final class StringUtilTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testRandom(): void
    {
        $length = 16;
        $random = StringUtil::random($length);
        $this->assertSame($length * 2, mb_strlen($random));

        $length = 32;
        $random = StringUtil::random($length);
        $this->assertSame($length * 2, mb_strlen($random));

        $length = 48;
        $random = StringUtil::random($length);
        $this->assertSame($length * 2, mb_strlen($random));

        $length = -1;
        $random = StringUtil::random($length);
        $this->assertSame(2, mb_strlen($random));
    }
}
