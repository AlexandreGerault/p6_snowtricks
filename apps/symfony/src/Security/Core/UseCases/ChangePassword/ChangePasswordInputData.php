<?php

namespace App\Security\Core\UseCases\ChangePassword;

class ChangePasswordInputData
{
    public function __construct(public readonly string $token, public readonly string $newPassword)
    {
    }
}