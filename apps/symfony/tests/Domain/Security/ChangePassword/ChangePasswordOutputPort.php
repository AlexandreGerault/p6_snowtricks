<?php

namespace App\Tests\Domain\Security\ChangePassword;

use App\Security\Core\UseCases\ChangePassword\ChangePasswordPresenter;
use PHPUnit\Framework\Assert;

class ChangePasswordOutputPort implements ChangePasswordPresenter
{
    private bool $userFound = false;
    private bool $passwordChanged = false;

    public function userNotFound(): void
    {
        $this->userFound = false;
    }

    public function assertPasswordChanged()
    {
        Assert::assertTrue($this->passwordChanged);
    }

    public function passwordChanged(): void
    {
        $this->userFound = true;
        $this->passwordChanged = true;
    }

    public function assertUserNotFound()
    {
        Assert::assertFalse($this->userFound);
    }
}