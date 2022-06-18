<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security\Register;

use App\Security\Core\PlainPassword;
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    public function test_a_user_can_register(): void
    {
        $sut = RegisterSUT::new()
            ->withEmail('user@email.fr')
            ->withPassword('password')
            ->withUsername('username')
            ->run();

        $sut->presenter->assertUserWasCreated();
        $sut->repository->assertCount(1);
        $sut->repository->assertUserWasCreated("user@email.fr");
        $sut->repository->assertPasswordIsHashedForEmail("user@email.fr", new PlainPassword("password"));
    }
}