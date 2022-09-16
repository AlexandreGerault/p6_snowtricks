<?php

declare(strict_types=1);

namespace App\Security\Core;

use Symfony\Component\Uid\AbstractUid;

class User
{
    public function __construct(
        private AbstractUid $id,
        private string $username,
        private string $email,
        private HashedPassword $password,
        private bool $activated = false,
        private ?ActivationToken $activationToken = null,
        private ?ResetPasswordToken $resetPasswordToken = null,
    ) {
    }

    public function snapshot(): UserSnapshot
    {
        return new UserSnapshot(
            id: $this->id,
            username: $this->username,
            email: $this->email,
            password: $this->password,
            activated: $this->activated,
            activationToken: $this->activationToken,
            passwordResetToken: $this->resetPasswordToken
        );
    }

    public function activate(): void
    {
        $this->activated = true;
    }

    public function changePasswordResetToken(ResetPasswordToken $resetPasswordToken): void
    {
        $this->resetPasswordToken = $resetPasswordToken;
    }
}
