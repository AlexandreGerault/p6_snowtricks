<?php

namespace App\Tests\Domain\Security\AskPasswordReset;

use App\Security\Core\ActivationToken;
use App\Security\Core\HashedPassword;
use App\Security\Core\ResetPasswordToken;
use App\Security\Core\User;
use App\Tests\Domain\Security\Adapters\FakePasswordResetTokenGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class AskPasswordResetTest extends TestCase
{
    public function test_a_user_can_ask_a_reset_password(): void
    {
        $user = new User(
            id: Uuid::fromString('8a817a66-a46f-4125-997d-94428ae56605'),
            username: 'username',
            email: 'user@example.com',
            password: new HashedPassword('password'),
            activated: false,
            activationToken: new ActivationToken("token")
        );

        $sut = AskPasswordResetSUT::new()
            ->withUser($user)
            ->withEmail('user@example.com')
            ->run();

        $sut->userRepository()->assertHasSaved();

        $sut->userRepository()->assertUserHasResetPasswordToken(
            Uuid::fromString('8a817a66-a46f-4125-997d-94428ae56605'),
            new ResetPasswordToken(FakePasswordResetTokenGenerator::TOKEN)
        );

        $sut->outputPort()->assertResetTokenCreated();
        $sut->notifications()->assertPasswordResetRequested();
    }

    public function test_it_presents_the_same_output_when_the_user_does_not_exist(): void
    {
        $user = new User(
            id: Uuid::fromString('8a817a66-a46f-4125-997d-94428ae56605'),
            username: 'username',
            email: 'user@example.com',
            password: new HashedPassword('password'),
            activated: false,
            activationToken: new ActivationToken("token")
        );

        $sut = AskPasswordResetSUT::new()
            ->withUser($user)
            ->withEmail('user-2@example.com')
            ->run();

        $sut->userRepository()->assertHasNotSaved();

        $sut->outputPort()->assertUserNotFound();
    }
}