<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Foundation;

use Exception;

final class StringUtil
{
    /**
     * @param int $length
     *
     * @throws Exception
     *
     * @return string
     */
    public static function random(int $length = 16): string
    {
        return bin2hex(random_bytes(max(1, $length)));
    }
}
