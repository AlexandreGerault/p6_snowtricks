<?php

declare(strict_types=1);

namespace App\Security\Infrastructure;

use App\Security\Infrastructure\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ActiveUserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        $this->checkAuth($user);
    }

    public function checkPostAuth(UserInterface $user): void
    {
        $this->checkAuth($user);
    }

    private function checkAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isActive()) {
            $exception = new CustomUserMessageAccountStatusException("Votre compte utilisateur n'a pas été activé.");
            $exception->setUser($user);
            throw $exception;
        }
    }
}
