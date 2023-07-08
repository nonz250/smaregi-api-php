<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Foundation;

use Exception;

final class PkceUtil
{
    private const CODE_VERIFIER_RANDOM_CHARS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-._~';

    private const CODE_VERIFIER_MIN_LENGTH = 43;

    private const CODE_VERIFIER_MAX_LENGTH = 128;

    private string $codeVerifier;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->codeVerifier = mb_substr(
            str_shuffle(self::CODE_VERIFIER_RANDOM_CHARS),
            0,
            random_int(self::CODE_VERIFIER_MIN_LENGTH, self::CODE_VERIFIER_MAX_LENGTH)
        );
    }

    public function codeVerifier(): string
    {
        return $this->codeVerifier;
    }

    public function codeChallenge(): string
    {
        return str_replace('=', '', strtr(base64_encode(hash('sha256', $this->codeVerifier, true)), '+/', '-_'));
    }
}
