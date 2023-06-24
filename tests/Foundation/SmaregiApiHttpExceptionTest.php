<?php
declare(strict_types=1);

namespace Tests\Foundation;

use Faker\Factory;
use Nonz250\SmaregiApiPhp\Foundation\SmaregiApiHttpException;
use PHPUnit\Framework\TestCase;

final class SmaregiApiHttpExceptionTest extends TestCase
{
    public function test__construct(): void
    {
        $faker = Factory::create();
        $expected = [
            $faker->text => $faker->text,
            $faker->text => $faker->text,
        ];
        $message = $faker->text;
        $exception = new SmaregiApiHttpException($expected, $message);
        $this->assertSame($expected, $exception->getResponse());
        $this->assertSame($message, $exception->getMessage());
    }
}
