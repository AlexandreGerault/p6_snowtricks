<?php

declare(strict_types=1);

namespace App\Security\Core;

use Symfony\Component\Uid\AbstractUid;

class User
{
    public function __construct(private AbstractUid $id, private string $username, private string $email, private string $password)
    {
    }

    public function snapshot(): UserSnapshot
    {
        return new UserSnapshot($this->id, $this->username, $this->email, $this->password);
    }
}