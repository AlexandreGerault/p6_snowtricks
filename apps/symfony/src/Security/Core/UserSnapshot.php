<?php

namespace App\Security\Core;

use Symfony\Component\Uid\AbstractUid;

class UserSnapshot
{
    public function __construct(private AbstractUid $id, private string $username, private string $email, private HashedPassword $password)
    {
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): HashedPassword
    {
        return $this->password;
    }

    public function username(): string
    {
        return $this->username;
    }
}
