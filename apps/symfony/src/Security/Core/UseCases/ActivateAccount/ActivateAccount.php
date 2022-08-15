<?php

namespace App\Security\Core\UseCases\ActivateAccount;

class ActivateAccount
{
    public function executes(ActivateAccountDTO $request, ActivateAccountPresenter $presenter): void
    {
        $presenter->userWasActivated();
    }
}