<?php

namespace App\Security\Core\UseCases\ActivateAccount;

use App\Security\Core\ActivationToken;
use App\Security\Core\UserRepository;

class ActivateAccount
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    public function executes(ActivateAccountInputData $request, ActivateAccountPresenter $presenter): void
    {
        $user = $this->repository->getFromActivationToken(new ActivationToken($request->userActivationToken));

        if (is_null($user)) {
            $presenter->userNotFound();

            return;
        }

        $user->activate();

        $this->repository->save($user);

        $presenter->userHasBeenActivated();
    }
}
