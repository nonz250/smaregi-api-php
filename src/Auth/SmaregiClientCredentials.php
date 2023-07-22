<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Auth;

use League\OAuth2\Client\Grant\ClientCredentials;

final class SmaregiClientCredentials extends ClientCredentials
{
    public const CONTRACT_ID_FIELD_NAME = 'contract_id';

    public const SCOPE_FIELD_NAME = 'scope';

    /**
     * @param array<string, mixed> $defaults
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    public function prepareRequestParameters(array $defaults, array $options): array
    {
        $provided = parent::prepareRequestParameters($defaults, $options);
        $provided[self::SCOPE_FIELD_NAME] = implode(' ', $provided[self::SCOPE_FIELD_NAME]);
        return $provided;
    }

    /**
     * @return string[]
     */
    protected function getRequiredRequestParameters(): array
    {
        return array_merge([
            self::CONTRACT_ID_FIELD_NAME,
        ], parent::getRequiredRequestParameters());
    }
}
