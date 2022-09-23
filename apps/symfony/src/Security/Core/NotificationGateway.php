<?php

declare(strict_types=1);

namespace App\Security\Core;

interface NotificationGateway
{
    public function notifyAccountCreated(UserSnapshot $user): void;

    public function notifyPasswordResetTokenCreated(User $user): void;
}
