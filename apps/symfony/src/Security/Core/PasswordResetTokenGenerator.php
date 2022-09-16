<?php

namespace App\Security\Core;

interface PasswordResetTokenGenerator
{
    public function generate(): ResetPasswordToken;
}