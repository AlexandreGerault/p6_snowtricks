<?php

namespace App\Security\Core;

class ActivationToken
{
    public function __construct(public readonly string $token)
    {
    }

    public function __toString(): string
    {
        return $this->token;
    }

    public function equals(ActivationToken $token): bool
    {
        return $this->token === $token->token;
    }
}
