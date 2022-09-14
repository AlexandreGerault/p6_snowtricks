<?php

namespace App\Tests\Domain\Security\Adapters;

use App\Security\Core\PlainPassword;
use App\Security\Core\ActivationToken;
use App\Security\Core\User;
use App\Security\Core\UserRepository;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\AbstractUid;

class InMemoryUserRepository implements UserRepository
{
    private bool $hasSaved = false;

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
            if ($user->snapshot()->email === $email) {
                return true;
            }
        }

        return false;
    }

    public function save(User $user): void
    {
        $this->users[] = $user;
        $this->hasSaved = true;
    }

    public function assertPasswordIsHashedForEmail(string $email, PlainPassword $password): void
    {
        foreach ($this->users as $user) {
            if ($user->snapshot()->email === $email) {
                Assert::assertTrue($user->snapshot()->password->value !== $password->value);
            }
        }
    }

    public function exists(string $email): bool
    {
        return $this->hasUser($email);
    }

    public function get(AbstractUid $id): User
    {
        foreach ($this->users as $user) {
            if ($user->snapshot()->id->equals($id)) {
                return $user;
            }
        }

        throw new \Exception('User not found');
    }

    public function assertHasSaved(): void
    {
        Assert::assertTrue($this->hasSaved);
    }

    public function getFromActivationToken(ActivationToken $token): ?User
    {
        foreach ($this->users as $user) {
            if ($user->snapshot()->activationToken->equals($token)) {
                return $user;
            }
        }

        return null;
    }
}
