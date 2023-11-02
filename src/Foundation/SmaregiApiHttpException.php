<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Foundation;

use RuntimeException;
use Throwable;

final class SmaregiApiHttpException extends RuntimeException
{
    /**
     * @var array<string, mixed>
     */
    private array $response;

    /**
     * @param array<string, mixed> $response
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(array $response, string $message = '', ?Throwable $previous = null)
    {
        $this->response = $response;
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return array<string, mixed>
     */
    public function getResponse(): array
    {
        return $this->response;
    }
}
