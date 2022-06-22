<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security\Adapters;

use App\Security\Core\NotificationGateway;
use App\Security\Core\UserSnapshot;
use PHPUnit\Framework\Assert;

class InMemoryNotifications implements NotificationGateway
{
    private array $notifications = [];

    public function notifyAccountCreated(UserSnapshot $user): void
    {
        $this->notifications[] = $user;
    }

    public function assertSent()
    {
        Assert::assertNotEmpty($this->notifications);
    }
}
