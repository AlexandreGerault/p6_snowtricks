<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security\ActivateAccount;

use App\Security\Core\UseCases\ActivateAccount\ActivateAccountPresenter;
use PHPUnit\Framework\Assert;

class ActivateAccountTestOutputPort implements ActivateAccountPresenter
{
    private bool $userWasActivated = false;

    public function assertUserWasActivated()
    {
        Assert::assertTrue($this->userWasActivated);
    }
}