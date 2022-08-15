<?php

declare(strict_types=1);

namespace App\Security\Core\UseCases\Register;

use App\Security\Core\PasswordHasher;
use App\Security\Core\PlainPassword;
use App\Security\Core\User;
use App\Security\Core\UserRepository;
use Symfony\Component\Uid\Uuid;

class Register
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly PasswordHasher $hasher,
        private readonly NotificationGateway $notificationGateway
    ) {
    }

    public function executes(RegisterInputData $input, RegisterPresenter $presenter): void
    {
        $isEmailAlreadyInUse = $this->repository->exists($input->email);

        if ($isEmailAlreadyInUse) {
            $presenter->emailAlreadyInUse();

            return;
        }

        $plainPassword = new PlainPassword($input->password);
        $hashedPassword = $this->hasher->hash($plainPassword);
        $user = new User(Uuid::v4(), $input->username, $input->email, $hashedPassword);

        $this->repository->save($user);

        $presenter->userCreated();
    }
}
