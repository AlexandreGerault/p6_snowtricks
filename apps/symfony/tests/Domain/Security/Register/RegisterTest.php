<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security\Register;

use App\Security\Core\HashedPassword;
use App\Security\Core\PlainPassword;
use App\Security\Core\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

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

    public function test_a_user_cannot_register_when_the_email_is_already_in_use(): void
    {
        $sut = RegisterSUT::new()
            ->whenHavingUsers([
                new User(Uuid::v4(), 'username', 'user@email.fr', new HashedPassword('password')),
            ])
            ->withEmail('user@email.fr')
            ->withPassword('password')
            ->withUsername('username')
            ->run();

        $sut->presenter->assertUserWasNotCreated();
        $sut->repository->assertCount(1);
        $sut->repository->assertUserWasCreated("user@email.fr");
        $sut->repository->assertPasswordIsHashedForEmail("user@email.fr", new PlainPassword("password"));
    }
}
