<?php

namespace App\Tests\Domain\Security\ActivateAccount;

use App\Security\Core\HashedPassword;
use App\Security\Core\ActivationToken;
use App\Security\Core\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ActivateAccountTest extends TestCase
{
    public function test_a_user_not_activated_activates_its_account(): void
    {
        $user = new User(
            id: Uuid::fromString('8a817a66-a46f-4125-997d-94428ae56605'),
            username: 'username',
            email: 'user@example.com',
            password: new HashedPassword('password'),
            activated: false,
            activationToken: new ActivationToken("token")
        );

        $sut = ActivateAccountSUT::new()
            ->withUser($user)
            ->run();

        $user = $sut->userRepository->get(Uuid::fromString('8a817a66-a46f-4125-997d-94428ae56605'))->snapshot();

        $this->assertTrue($user->activated);
        $sut->userRepository->assertHasSaved();
        $sut->presenter->assertUserWasActivated();
    }

    public function test_an_invalid_token_cannot_activate_a_user(): void
    {
        $user = new User(
            id: Uuid::fromString('8a817a66-a46f-4125-997d-94428ae56605'),
            username: 'username',
            email: 'user@example.com',
            password: new HashedPassword('password'),
            activated: false,
            activationToken: new ActivationToken("token")
        );

        $sut = ActivateAccountSUT::new()
            ->withUser($user)
            ->withActivationToken("invalid-token")
            ->run();

        $user = $sut->userRepository->get(Uuid::fromString('8a817a66-a46f-4125-997d-94428ae56605'))->snapshot();

        $this->assertFalse($user->activated);
        $sut->presenter->assertUserNotFound();
    }
}