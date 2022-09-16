<?php

declare(strict_types=1);

namespace App\Security\Core\UseCases\ActivateAccount;

interface ActivateAccountPresenter
{
    public function userHasBeenActivated(): void;

    public function userNotFound(): void;
}
