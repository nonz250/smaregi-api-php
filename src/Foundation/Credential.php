<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Foundation;

final class Credential
{
    private string $clientId;

    private string $clientSecret;

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return array<string, string>
     */
    public function __debugInfo(): array
    {
        return [
            'clientId' => $this->clientId,
            'clientSecret' => '(secret)',
        ];
    }

    public function authorizationBasic(): string
    {
        return 'Basic ' . base64_encode("$this->clientId:$this->clientSecret");
    }
}
