<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Auth;

use League\OAuth2\Client\OptionProvider\HttpBasicAuthOptionProvider;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use UnexpectedValueException;

final class SmaregiProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    private const CONTRACT_ID = ':contractId';

    private const AUTHORIZATION_ENDPOINT = '/authorize';

    private const APP_TOKEN_ENDPOINT = '/app/' . self::CONTRACT_ID . '/token';

    private const TOKEN_ENDPOINT = '/authorize/token';

    private const USER_INFO_ENDPOINT = '/userinfo';

    private string $host;

    public function __construct(string $host, string $clientId, string $secret, string $redirectUri = null)
    {
        $this->host = $host;

        parent::__construct([
            'clientId' => $clientId,
            'clientSecret' => $secret,
            'redirectUri' => $redirectUri,
            'timeout' => 6.0,
            'connect_timeout' => 1.8,
            'read_timeout' => 1.2,
            'headers' => [
                'User-Agent' => 'nonz250/smaregi-api-php',
            ],
        ], [
            'optionProvider' => new HttpBasicAuthOptionProvider(),
        ]);
    }

    public function getBaseAuthorizationUrl(): string
    {
        return $this->host . self::AUTHORIZATION_ENDPOINT;
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        if (array_key_exists(SmaregiClientCredentials::CONTRACT_ID_FIELD_NAME, $params) && $params['grant_type'] === 'client_credentials') {
            return $this->host . str_replace(
                self::CONTRACT_ID,
                (string)$params[SmaregiClientCredentials::CONTRACT_ID_FIELD_NAME], // @phpstan-ignore-line
                self::APP_TOKEN_ENDPOINT,
            );
        }
        return $this->host . self::TOKEN_ENDPOINT;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->host . self::USER_INFO_ENDPOINT;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param array<string, mixed> $params
     *
     * @return RequestInterface
     */
    protected function getAccessTokenRequest(array $params): RequestInterface
    {
        $method = $this->getAccessTokenMethod();
        $url = $this->getAccessTokenUrl($params);

        // 不要なparamsを削除する。
        unset($params[SmaregiClientCredentials::CONTRACT_ID_FIELD_NAME]);

        $options = $this->optionProvider->getAccessTokenOptions($this->getAccessTokenMethod(), $params);

        return $this->getRequest($method, $url, $options);
    }

    /**
     * @codeCoverageIgnore
     *
     * @param AccessToken $token
     *
     * @throws IdentityProviderException
     *
     * @return array|mixed|string
     */
    protected function fetchResourceOwnerDetails(AccessToken $token): mixed
    {
        $url = $this->getResourceOwnerDetailsUrl($token);

        $request = $this->getAuthenticatedRequest(self::METHOD_POST, $url, $token);

        $response = $this->getParsedResponse($request);

        if (false === is_array($response)) {
            throw new UnexpectedValueException(
                'Invalid response received from Authorization Server. Expected JSON.'
            );
        }

        return $response;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string
     */
    protected function getScopeSeparator(): string
    {
        return ' ';
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string
     */
    protected function getPkceMethod(): string
    {
        return parent::PKCE_METHOD_S256;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string[]
     */
    protected function getDefaultScopes(): array
    {
        return ['openid', 'email', 'profile', 'offline_access'];
    }

    /**
     * @codeCoverageIgnore
     *
     * @param ResponseInterface $response
     * @param array<string, mixed> $data
     *
     * @throws IdentityProviderException
     *
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (is_array($data) & array_key_exists('error', $data)) {
            $errors = (string)var_export($data, true);
            throw new IdentityProviderException($errors, 0, $data);
        }
    }

    /**
     * @codeCoverageIgnore
     *
     * @param array<string, bool|int|string> $response
     * @param AccessToken $token
     *
     * @return ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new SmaregiUser($response);
    }
}
