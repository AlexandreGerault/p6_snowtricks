<?php

namespace App\Tests\Domain\Security\ChangePassword;

use App\Security\Core\UseCases\ChangePassword\ChangePassword;
use App\Security\Core\UseCases\ChangePassword\ChangePasswordInputData;
use App\Security\Core\User;
use App\Tests\Domain\Security\Adapters\FakePasswordHasher;
use App\Tests\Domain\Security\Adapters\InMemoryUserRepository;

class ChangePasswordSUT
{
    private User $user;
    private string $token;
    private string $newPassword;
    private InMemoryUserRepository $userRepository;
    private FakePasswordHasher $passwordHasher;
    private ChangePasswordOutputPort $presenter;

    public static function new(): ChangePasswordSUT
    {
        return new self();
    }

    public function withUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function withToken(string $string): static
    {
        $this->token = $string;

        return $this;
    }

    public function withNewPassword(string $string): static
    {
        $this->newPassword = $string;

        return $this;
    }

    public function run(): static
    {
        $this->userRepository ??= new InMemoryUserRepository([$this->user]);
        $this->passwordHasher ??= new FakePasswordHasher();

        $input = new ChangePasswordInputData($this->token, $this->newPassword);
        $this->presenter = new ChangePasswordOutputPort();

        $changePassword = new ChangePassword($this->userRepository, $this->passwordHasher);
        $changePassword->executes($input, $this->presenter);

        return $this;
    }

    public function userRepository(): InMemoryUserRepository
    {
        return $this->userRepository;
    }

    public function output(): ChangePasswordOutputPort
    {
        return $this->presenter;
    }
}