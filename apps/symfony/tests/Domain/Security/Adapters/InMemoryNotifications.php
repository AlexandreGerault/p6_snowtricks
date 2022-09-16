<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security\Adapters;

use App\Security\Core\NotificationGateway;
use App\Security\Core\User;
use App\Security\Core\UserSnapshot;
use PHPUnit\Framework\Assert;

class InMemoryNotifications implements NotificationGateway
{
    private bool $accountCreated = false;
    private bool $passwordReset = false;

    public function notifyAccountCreated(UserSnapshot $user): void
    {
        $this->accountCreated = true;
    }

    public function assertAccountCreatedSent()
    {
        Assert::assertTrue($this->accountCreated, 'Account created notification was not sent');
    }

    public function assertPasswordResetRequested()
    {
        Assert::assertTrue($this->passwordReset, 'Password reset notification was not sent');
    }

    public function notifyPasswordResetTokenCreated(User $user): void
    {
        $this->passwordReset = true;
    }
}
