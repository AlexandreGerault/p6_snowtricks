<?php

declare(strict_types=1);

namespace App\Security\Core;

use Symfony\Component\Uid\AbstractUid;

interface UserRepository
{
    public function exists(string $email): bool;

    public function get(AbstractUid $id): ?User;

    public function getFromActivationToken(ActivationToken $token): ?User;

    public function save(User $user): void;

    public function findByEmail(string $email): ?User;
}
