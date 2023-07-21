<?php
declare(strict_types=1);

namespace Tests\Login;

use Exception;
use Faker\Factory;
use Nonz250\SmaregiApiPhp\Login\SmaregiUser;
use PHPUnit\Framework\TestCase;

final class SmaregiUserTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test__construct(): void
    {
        $faker = Factory::create();

        $expected = [
            'sub' => $faker->text,
            'contract' => [
                'id' => $faker->text,
                'user_id' => $faker->text,
                'is_owner' => (bool)(random_int(0, 1)),
            ],
            'name' => $faker->text,
            'email' => $faker->safeEmail,
            'email_verified' => (bool)(random_int(0, 1)),
        ];

        $smaregiUser = new SmaregiUser($expected);

        $this->assertSame($expected, $smaregiUser->toArray());
        $this->assertSame($expected['sub'], $smaregiUser->getId());
        $this->assertSame($expected['contract']['id'], $smaregiUser->getContractId());
        $this->assertSame($expected['contract']['user_id'], $smaregiUser->getUserId());
        $this->assertSame($expected['contract']['is_owner'], $smaregiUser->getIsOwner());
        $this->assertSame($expected['name'], $smaregiUser->getName());
        $this->assertSame($expected['email'], $smaregiUser->getEmail());
        $this->assertSame($expected['email_verified'], $smaregiUser->getEmailVerified());
    }
}
