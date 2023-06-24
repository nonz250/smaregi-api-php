<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Foundation;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

final class PsrFactories
{
    private RequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    private ResponseFactoryInterface $responseFactory;

    private UriFactoryInterface $uriFactory;

    public function __construct(
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        ResponseFactoryInterface $responseFactory,
        UriFactoryInterface $uriFactory
    ) {
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->responseFactory = $responseFactory;
        $this->uriFactory = $uriFactory;
    }

    public function getRequestFactory(): RequestFactoryInterface
    {
        return $this->requestFactory;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getResponseFactory(): ResponseFactoryInterface
    {
        return $this->responseFactory;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getStreamFactory(): StreamFactoryInterface
    {
        return $this->streamFactory;
    }

    public function getUriFactory(): UriFactoryInterface
    {
        return $this->uriFactory;
    }
}
