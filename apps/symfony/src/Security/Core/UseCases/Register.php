<?php

declare(strict_types=1);

namespace App\Security\Core\UseCases;

use App\Security\Core\User;
use App\Security\Core\UserRepository;
use Symfony\Component\Uid\Uuid;

class Register
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    public function executes(RegisterPresenter $presenter): void
    {
        $this->repository->save(new User(Uuid::v4(), 'username', 'user@email.fr', 'password'));
        $presenter->userCreated();
    }
}