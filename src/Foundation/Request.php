<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Foundation;

use JsonException;
use LogicException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * @codeCoverageIgnore
 */
abstract class Request
{
    protected const PATH = '';

    /**
     * @var array<string, string>
     */
    protected array $payload = [];

    /**
     * @param RequestInterface $request
     * @param ContentType $contentType
     *
     * @throws JsonException
     *
     * @return RequestInterface
     */
    public function toPsrRequest(RequestInterface $request, ContentType $contentType = ContentType::JSON): RequestInterface
    {
        $request = $request
            ->withUri($this->endpointUri($request->getUri()));

        if ($contentType->equals(ContentType::FORM_URLENCODED)) {
            $request->getBody()->write(http_build_query($this->payload));
        } elseif ($contentType->equals(ContentType::JSON)) {
            $request->getBody()->write(json_encode($this->payload, JSON_THROW_ON_ERROR));
        } else {
            // @codeCoverageIgnoreStart
            throw new LogicException(
                sprintf(
                    'The only supported `Content-Type` are %s and %s',
                    ContentType::FORM_URLENCODED->value,
                    ContentType::JSON->value,
                )
            );
            // @codeCoverageIgnoreEnd
        }

        return $request;
    }

    protected function endpointUri(UriInterface $uri): UriInterface
    {
        return $uri->withPath(self::PATH);
    }
}
