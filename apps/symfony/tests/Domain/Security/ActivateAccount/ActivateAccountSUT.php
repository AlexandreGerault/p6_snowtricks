<?php

namespace App\Tests\Domain\Security\ActivateAccount;

use App\Security\Core\UseCases\ActivateAccount\ActivateAccount;

class ActivateAccountSUT
{
    public ActivateAccountTestOutputPort $presenter;

    private function __construct()
    {
        $this->presenter = new ActivateAccountTestOutputPort();
    }

    public static function new(): static
    {
        return new self();
    }

    public function run(): static
    {
        $activateAccount = new ActivateAccount();

        $activateAccount->run(
            ActivateAccountDTO::new()->withActivationToken(ActivationTokenFixture::ACTIVATION_TOKEN),
            $this->presenter
        );

        return $this;
    }
}
