<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Login;

use Nonz250\SmaregiApiPhp\Login\Authorize\AuthorizeRequest;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    /**
     * @param AuthorizeRequest $authorizeRequest
     *
     * @return ResponseInterface
     */
    public function authorize(AuthorizeRequest $authorizeRequest): ResponseInterface;
}
