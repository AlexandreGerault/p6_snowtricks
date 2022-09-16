<?php

namespace App\Tests\Domain\Security\Adapters;

use App\Security\Core\PasswordResetTokenGenerator;
use App\Security\Core\ResetPasswordToken;

class FakePasswordResetTokenGenerator implements PasswordResetTokenGenerator
{
    public const TOKEN = 'fake-token';

    public function generate(): ResetPasswordToken
    {
        return new ResetPasswordToken(self::TOKEN);
    }
}
