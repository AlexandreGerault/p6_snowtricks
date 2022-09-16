<?php

namespace App\Security\Core\UseCases\AskPasswordReset;

interface AskPasswordResetPresenter
{
    public function resetTokenCreated(): void;

    public function userNotFound(): void;
}