<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Login;

use Fig\Http\Message\RequestMethodInterface;
use Nonz250\SmaregiApiPhp\Foundation\Credential;
use Nonz250\SmaregiApiPhp\Foundation\PsrFactories;
use Nonz250\SmaregiApiPhp\Login\Authorize\AuthorizeRequest;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

final class Client implements \Nonz250\SmaregiApiPhp\Login\ClientInterface
{
    private UriInterface $uri;

    private Credential $credential;

    private ClientInterface $httpClient;

    private PsrFactories $psrFactories;

    public function __construct(
        UriInterface $uri,
        Credential $credential,
        ClientInterface $client,
        PsrFactories $psrFactories
    ) {
        $this->uri = $uri;
        $this->credential = $credential;
        $this->httpClient = $client;
        $this->psrFactories = $psrFactories;
    }

    /**
     * @param AuthorizeRequest $authorizeRequest
     *
     * @throws ClientExceptionInterface
     *
     * @return ResponseInterface
     */
    public function authorize(AuthorizeRequest $authorizeRequest): ResponseInterface
    {
        $request = $authorizeRequest
            ->withClientId($this->credential->clientId())
            ->toPsrRequest($this->newRequest());
        return $this->sendRequest($request);
    }

    private function newRequest(): RequestInterface
    {
        return $this->psrFactories
            ->getRequestFactory()
            ->createRequest(RequestMethodInterface::METHOD_GET, $this->uri);
    }

    /**
     * @param RequestInterface $request
     *
     * @throws ClientExceptionInterface
     *
     * @return ResponseInterface
     */
    private function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->sendRequest($request);
    }
}
