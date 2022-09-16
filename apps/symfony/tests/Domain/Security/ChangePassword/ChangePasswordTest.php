<?php

namespace App\Tests\Domain\Security\ChangePassword;

use App\Security\Core\ActivationToken;
use App\Security\Core\HashedPassword;
use App\Security\Core\ResetPasswordToken;
use App\Security\Core\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ChangePasswordTest extends TestCase
{
    public function testAUserCanChangeItsPasswordWhenItProvidesTheCorrectToken(): void
    {
        $user = new User(
            id: Uuid::fromString('8a817a66-a46f-4125-997d-94428ae56605'),
            username: 'username',
            email: 'user@example.com',
            password: new HashedPassword('password'),
            activated: false,
            activationToken: new ActivationToken('token'),
            resetPasswordToken: new ResetPasswordToken('token')
        );

        $sut = ChangePasswordSUT::new()
            ->withUser($user)
            ->withToken('token')
            ->withNewPassword('new-password')
            ->run();

        $sut->userRepository()->assertHasSaved();
        $sut->output()->assertPasswordChanged();
        $this->assertEquals(
            new HashedPassword('new-password_hashed'),
            $user->snapshot()->password
        );
    }

    public function testItDoesNotFoundTheUserWhenTheTokenIsIncorrect(): void
    {
        $user = new User(
            id: Uuid::fromString('8a817a66-a46f-4125-997d-94428ae56605'),
            username: 'username',
            email: 'user@example.com',
            password: new HashedPassword('password'),
            activated: false,
            activationToken: new ActivationToken('token'),
            resetPasswordToken: new ResetPasswordToken('token')
        );

        $sut = ChangePasswordSUT::new()
            ->withUser($user)
            ->withToken('wrong-token')
            ->withNewPassword('new-password')
            ->run();

        $sut->userRepository()->assertHasNotSaved();
        $sut->output()->assertUserNotFound();
        $this->assertEquals(
            new HashedPassword('password'),
            $user->snapshot()->password
        );
    }
}
