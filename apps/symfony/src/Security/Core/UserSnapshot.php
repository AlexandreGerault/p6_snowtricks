<?php

namespace App\Security\Core;

use Symfony\Component\Uid\AbstractUid;

class UserSnapshot
{
    public function __construct(private AbstractUid $id, private string $username, private string $email, private string $password)
    {
    }

    public function email(): string
    {
        return $this->email;
    }
}