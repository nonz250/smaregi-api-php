<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Auth;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

final class SmaregiUser implements ResourceOwnerInterface
{
    /** @var array<string, mixed> */
    private array $data;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId(): string
    {
        return (string)($this->data['sub'] ?? ''); // @phpstan-ignore-line
    }

    public function getContractId(): string
    {
        return (string)($this->data['contract']['id'] ?? ''); // @phpstan-ignore-line
    }

    public function getUserId(): string
    {
        return (string)($this->data['contract']['user_id'] ?? ''); // @phpstan-ignore-line
    }

    public function getIsOwner(): bool
    {
        return (bool)($this->data['contract']['is_owner'] ?? false); // @phpstan-ignore-line
    }

    public function getName(): string
    {
        return (string)($this->data['name'] ?? ''); // @phpstan-ignore-line
    }

    public function getEmail(): string
    {
        return (string)($this->data['email'] ?? ''); // @phpstan-ignore-line
    }

    public function getEmailVerified(): bool
    {
        return (bool)($this->data['email_verified'] ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
