<?php
declare(strict_types=1);

namespace Tests\Foundation;

use Nonz250\SmaregiApiPhp\Foundation\ContentType;
use PHPUnit\Framework\TestCase;

final class ContentTypeTest extends TestCase
{
    public function testContentType(): void
    {
        $this->assertTrue(ContentType::FORM_URLENCODED->equals(ContentType::FORM_URLENCODED));
        $this->assertFalse(ContentType::FORM_URLENCODED->equals(ContentType::JSON));
        $this->assertTrue(ContentType::FORM_URLENCODED->equals(ContentType::FORM_URLENCODED));
        $this->assertFalse(ContentType::JSON->equals(ContentType::FORM_URLENCODED));
    }
}
