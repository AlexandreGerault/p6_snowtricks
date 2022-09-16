<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security\Register;

use App\Security\Core\UseCases\Register\RegisterPresenter;
use PHPUnit\Framework\Assert;

class RegisterTestOutputPort implements RegisterPresenter
{
    private bool $userWasCreated = false;

    public function assertUserWasCreated()
    {
        Assert::assertTrue($this->userWasCreated);
    }

    public function userCreated(): void
    {
        $this->userWasCreated = true;
    }

    public function emailAlreadyInUse(): void
    {
        // TODO: Implement emailAlreadyInUse() method.
    }

    public function assertUserWasNotCreated(): void
    {
        Assert::assertFalse($this->userWasCreated);
    }
}
