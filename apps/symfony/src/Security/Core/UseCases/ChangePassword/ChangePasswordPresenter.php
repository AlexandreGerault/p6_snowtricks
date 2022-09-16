<?php

namespace App\Security\Core\UseCases\ChangePassword;

interface ChangePasswordPresenter
{
    public function userNotFound(): void;

    public function passwordChanged(): void;
}
