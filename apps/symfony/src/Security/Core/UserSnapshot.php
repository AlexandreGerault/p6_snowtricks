<?php

namespace App\Security\Core;

use Symfony\Component\Uid\AbstractUid;

class UserSnapshot
{
    public function __construct(
        public readonly AbstractUid         $id,
        public readonly string              $username,
        public readonly string              $email,
        public readonly HashedPassword      $password,
        public readonly bool                $activated,
        public readonly ?ActivationToken    $activationToken = null,
        public readonly ?ResetPasswordToken $passwordResetToken = null,
    )
    {
    }
}
