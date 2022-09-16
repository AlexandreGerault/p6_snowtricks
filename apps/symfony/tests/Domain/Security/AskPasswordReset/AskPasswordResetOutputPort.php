<?php

namespace App\Tests\Domain\Security\AskPasswordReset;

use App\Security\Core\UseCases\AskPasswordReset\AskPasswordResetPresenter;
use PHPUnit\Framework\Assert;

class AskPasswordResetOutputPort implements AskPasswordResetPresenter
{
    private bool $resetTokenCreated = false;
    private bool $userNotFound = false;

    public function assertResetTokenCreated(): void
    {
        Assert::assertTrue($this->resetTokenCreated);
    }

    public function resetTokenCreated(): void
    {
        $this->resetTokenCreated = true;
    }

    public function assertUserNotFound(): void
    {
        Assert::assertTrue($this->userNotFound);
    }

    public function userNotFound(): void
    {
        $this->userNotFound = true;
    }
}