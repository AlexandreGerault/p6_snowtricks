<?php

declare(strict_types=1);

namespace App\Security\Core\UseCases;

interface RegisterPresenter
{
    public function userCreated(): void;
}