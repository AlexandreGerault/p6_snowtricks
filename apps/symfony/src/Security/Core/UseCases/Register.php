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

    public function executes(RegisterInputData $input, RegisterPresenter $presenter): void
    {
        $user = new User(Uuid::v4(), $input->username, $input->email, $input->password);

        $this->repository->save($user);

        $presenter->userCreated();
    }
}