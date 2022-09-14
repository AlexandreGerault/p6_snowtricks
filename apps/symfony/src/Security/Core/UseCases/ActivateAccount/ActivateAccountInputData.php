<?php

namespace App\Security\Core\UseCases\ActivateAccount;

class ActivateAccountInputData
{
    public function __construct(public readonly string $userActivationToken)
    {
    }
}