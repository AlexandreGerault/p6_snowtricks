<?php

namespace App\Security\Core\UseCases\AskPasswordReset;

class AskPasswordResetInputData
{
    public function __construct(public readonly string $username)
    {
    }
}
