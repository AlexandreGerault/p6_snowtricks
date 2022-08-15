<?php

namespace App\Tests\Domain\Security\ActivateAccount;

use PHPUnit\Framework\TestCase;

class ActivateAccountTest extends TestCase
{
    public function test_a_user_not_activated_activates_its_account()
    {
        $sut = ActivateAccountSUT::new()->run();

        $sut->presenter->assertUserWasActivated();
    }
}