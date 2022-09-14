<?php

namespace App\Tests\Domain\Security\ActivateAccount;

use App\Security\Core\ActivationToken;
use App\Security\Core\UseCases\ActivateAccount\ActivateAccount;
use App\Security\Core\UseCases\ActivateAccount\ActivateAccountInputData;
use App\Security\Core\User;
use App\Security\Core\UserRepository;
use App\Tests\Domain\Security\Adapters\InMemoryUserRepository;

class ActivateAccountSUT
{
    public ActivateAccountTestOutputPort $presenter;
    public InMemoryUserRepository $userRepository;
    private ActivationToken $activationToken;

    private User $user;

    private function __construct()
    {
        $this->presenter = new ActivateAccountTestOutputPort();
    }

    public static function new(): static
    {
        return new self();
    }

    public function withActivationToken(string $token): static
    {
        $this->activationToken = new ActivationToken($token);

        return $this;
    }

    public function withUser(User $user): static
    {
        $this->user = $user;
        $this->activationToken = $user->snapshot()->activationToken;

        return $this;
    }

    public function run(): static
    {
        $input = new ActivateAccountInputData($this->activationToken->token);
        $this->userRepository = new InMemoryUserRepository([$this->user]);

        $activateAccount = new ActivateAccount($this->userRepository);
        $activateAccount->executes($input, $this->presenter);

        return $this;
    }
}
