<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Client;

use Nonz250\SmaregiApiPhp\Client\Token\TokenRequest;

interface ClientInterface
{
    /**
     * @param TokenRequest $tokenRequest
     *
     * @return array<string, mixed>
     */
    public function token(TokenRequest $tokenRequest): array;
}
