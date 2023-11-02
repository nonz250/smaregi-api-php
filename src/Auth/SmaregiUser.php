<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Auth;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

final class SmaregiUser implements ResourceOwnerInterface
{
    /** @var array<string, bool|int|string> */
    private array $data;

    /**
     * @param array<string, bool|int|string> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId(): string
    {
        return (string)($this->data['sub'] ?? '');
    }

    public function getContractId(): string
    {
        return (string)($this->data['contract']['id'] ?? '');
    }

    public function getUserId(): string
    {
        return (string)($this->data['contract']['user_id'] ?? '');
    }

    public function getIsOwner(): bool
    {
        return (bool)($this->data['contract']['is_owner'] ?? false);
    }

    public function getName(): string
    {
        return (string)($this->data['name'] ?? '');
    }

    public function getEmail(): string
    {
        return (string)($this->data['email'] ?? '');
    }

    public function getEmailVerified(): bool
    {
        return (bool)($this->data['email_verified'] ?? false);
    }

    /**
     * @return array<string, bool|int|string>
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
