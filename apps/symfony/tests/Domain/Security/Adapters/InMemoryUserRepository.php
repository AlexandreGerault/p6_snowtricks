<?php

namespace App\Tests\Domain\Security\Adapters;

use App\Security\Core\PlainPassword;
use App\Security\Core\User;
use App\Security\Core\UserRepository;
use PHPUnit\Framework\Assert;

class InMemoryUserRepository implements UserRepository
{
    /** @param User[] $users */
    public function __construct(private array $users = [])
    {
    }

    public function assertUserWasCreated(string $email)
    {
        Assert::assertTrue($this->hasUser($email));
    }

    public function assertCount(int $count)
    {
        Assert::assertCount($count, $this->users);
    }

    private function hasUser(string $email): bool
    {
        foreach ($this->users as $user) {
            if ($user->snapshot()->email() === $email) {
                return true;
            }
        }

        return false;
    }

    public function save(User $user): void
    {
        $this->users[] = $user;
    }

    public function assertPasswordIsHashedForEmail(string $email, PlainPassword $password): void
    {
        foreach ($this->users as $user) {
            if ($user->snapshot()->email() === $email) {
                Assert::assertTrue($user->snapshot()->password()->value !== $password->value);
            }
        }
    }
}