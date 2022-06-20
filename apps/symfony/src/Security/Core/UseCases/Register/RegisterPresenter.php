<?php

declare(strict_types=1);

namespace App\Security\Core\UseCases\Register;

interface RegisterPresenter
{
    public function userCreated(): void;
}