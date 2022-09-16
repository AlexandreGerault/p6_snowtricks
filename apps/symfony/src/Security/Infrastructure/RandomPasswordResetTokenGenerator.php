<?php

namespace App\Security\Infrastructure;

use App\Security\Core\PasswordResetTokenGenerator;
use App\Security\Core\ResetPasswordToken;

class RandomPasswordResetTokenGenerator implements PasswordResetTokenGenerator
{
    /** @throws \Exception */
    public function generate(): ResetPasswordToken
    {
        return new ResetPasswordToken(sha1(random_bytes(16)));
    }
}