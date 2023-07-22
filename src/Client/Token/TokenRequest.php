<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Client\Token;

use Fig\Http\Message\RequestMethodInterface;
use Nonz250\SmaregiApiPhp\Foundation\ContentType;
use Nonz250\SmaregiApiPhp\Foundation\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * @deprecated Auth\SmaregiProvider を利用してください。
 */
final class TokenRequest extends Request
{
    protected const METHOD = RequestMethodInterface::METHOD_POST;

    protected const PATH = '/app/' . self::CONTRACT_ID . '/token';

    private const CONTRACT_ID = ':contractId';

    /**
     * @var array<string, string>
     */
    protected array $payload = [
        'grant_type' => 'client_credentials',
        'scope' => '',
    ];

    private string $contractId;

    public function __construct(string $contractId)
    {
        $this->contractId = $contractId;
    }

    public function toPsrRequest(RequestInterface $request, ContentType $contentType = ContentType::JSON): RequestInterface
    {
        return parent::toPsrRequest($request, $contentType)
            ->withMethod(self::METHOD);
    }

    /**
     * @param string[] $scopes
     *
     * @return $this
     */
    public function withScopes(array $scopes): self
    {
        $new = clone $this;
        $new->payload['scope'] = implode(' ', $scopes);
        return $new;
    }

    protected function endpointUri(UriInterface $uri): UriInterface
    {
        return $uri->withPath(str_replace(self::CONTRACT_ID, $this->contractId, self::PATH));
    }
}
