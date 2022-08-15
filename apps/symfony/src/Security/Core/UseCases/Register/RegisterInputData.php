<?php

declare(strict_types=1);

namespace App\Security\Core\UseCases\Register;

class RegisterInputData
{
    public function __construct(public readonly string $username, public readonly string $email, public readonly string $password)
    {
    }
}
