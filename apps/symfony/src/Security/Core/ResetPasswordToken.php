<?php

namespace App\Security\Core;

class ResetPasswordToken
{
    public function __construct(public readonly string $token)
    {
    }

    public function __toString(): string
    {
        return $this->token;
    }

    public function equals(ResetPasswordToken $token): bool
    {
        return $this->token === $token->token;
    }
}
