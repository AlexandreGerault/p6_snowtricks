<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security\ActivateAccount;

use App\Security\Core\UseCases\ActivateAccount\ActivateAccountPresenter;
use PHPUnit\Framework\Assert;

class ActivateAccountTestOutputPort implements ActivateAccountPresenter
{
    private bool $userWasActivated = false;
    private bool $userFound;

    public function assertUserWasActivated()
    {
        Assert::assertTrue($this->userWasActivated);
    }

    public function userHasBeenActivated(): void
    {
        $this->userWasActivated = true;
        $this->userFound = true;
    }

    public function assertUserNotFound()
    {
        Assert::assertFalse($this->userFound);
    }

    public function userNotFound(): void
    {
        $this->userFound = false;
    }
}
