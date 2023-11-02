<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Client;

use Fig\Http\Message\RequestMethodInterface;
use Fig\Http\Message\StatusCodeInterface;
use JsonException;
use Nonz250\SmaregiApiPhp\Foundation\ContentType;
use Nonz250\SmaregiApiPhp\Foundation\Credential;
use Nonz250\SmaregiApiPhp\Foundation\PsrFactories;
use Nonz250\SmaregiApiPhp\Foundation\SmaregiApiHttpException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

final class Client implements \Nonz250\SmaregiApiPhp\Client\ClientInterface
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
        $request = $request
            ->withHeader('Authorization', $this->credential->authorizationBasic())
            ->withHeader('Content-Type', ContentType::FORM_URLENCODED->value);

        return $this->httpClient->sendRequest($request);
    }

    /**
     * @param ResponseInterface $response
     *
     * @throws JsonException
     */
    private function validation(ResponseInterface $response): void
    {
        if ($response->getStatusCode() >= StatusCodeInterface::STATUS_BAD_REQUEST) {
            $responseBody = (array)json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            throw new SmaregiApiHttpException(
                $responseBody,
                array_key_exists('title', $responseBody)
                    ? $responseBody['title']
                    : 'Smaregi api client error.'
            );
        }
    }
}
