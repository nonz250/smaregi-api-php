<?php
declare(strict_types=1);

namespace Nonz250\SmaregiApiPhp\Foundation;

enum ContentType: string
{
    case JSON = 'application/json';
    case FORM_URLENCODED = 'application/x-www-form-urlencoded';

    public function equals(self $contentType): bool
    {
        return $this->value === $contentType->value;
    }
}
