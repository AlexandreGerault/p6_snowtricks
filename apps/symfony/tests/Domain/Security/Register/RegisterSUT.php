<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security\Register;

use App\Security\Core\UseCases\Register;
use App\Security\Core\UseCases\RegisterInputData;
use App\Security\Core\User;
use App\Tests\Domain\Security\Adapters\FakePasswordHasher;
use App\Tests\Domain\Security\Adapters\InMemoryUserRepository;

class RegisterSUT
{
    private string $email;
    private string $password;
    private string $username;
    public InMemoryUserRepository $repository;
    public RegisterTestOutputPort $presenter;

    /** @var User[] */
    private array $users = [];

    public function __construct()
    {
    }

    public static function new(): RegisterSUT
    {
        return new self();
    }

    public function run(): static
    {
        $this->presenter = new RegisterTestOutputPort();
        $this->repository = new InMemoryUserRepository($this->users);

        $register = new Register($this->repository, new FakePasswordHasher());
        $register->executes(
            new RegisterInputData($this->username, $this->email, $this->password),
            $this->presenter
        );

        return $this;
    }

    public function withEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function withPassword(string $rawPassword): static
    {
        $this->password = $rawPassword;

        return $this;
    }

    public function withUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /** @param  User[]  $users */
    public function whenHavingUsers(array $users): static
    {
        $this->users = $users;

        return $this;
    }
}
