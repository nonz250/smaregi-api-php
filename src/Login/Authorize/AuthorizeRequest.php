<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Login\Authorize;

use Exception;
use Nonz250\SmaregiApiPhp\Foundation\PkceUtil;
use Nonz250\SmaregiApiPhp\Foundation\Request;
use Nonz250\SmaregiApiPhp\Foundation\StringUtil;
use Psr\Http\Message\UriInterface;

final class AuthorizeRequest extends Request
{
    protected const PATH = '/authorize';

    /**
     * @var array<string, string>
     */
    protected array $params = [
        'response_type' => 'code',
        'client_id' => '',
        'scope' => '',
        'state' => '',
        'code_challenge' => '',
        'code_challenge_method' => 'S256',
    ];

    /**
     * @param string $clientId
     *
     * @throws Exception
     */
    public function __construct(string $clientId)
    {
        $this->params['client_id'] = $clientId;
        $this->params['state'] = StringUtil::random();
        $this->params['code_challenge'] = (new PkceUtil())->codeChallenge();
    }

    /**
     * @param string[] $scopes
     *
     * @return $this
     */
    public function withScopes(array $scopes): self
    {
        $new = clone $this;
        $new->params['scope'] = implode(' ', $scopes);
        return $new;
    }

    /**
     * @param string $uri
     *
     * @return $this
     */
    public function withRedirectUri(string $uri): self
    {
        $new = clone $this;
        $new->params['redirect_uri'] = $uri;
        return $new;
    }

    protected function endpointUri(UriInterface $uri): UriInterface
    {
        return $uri->withPath(self::PATH . '/' . http_build_query($this->params));
    }
}
