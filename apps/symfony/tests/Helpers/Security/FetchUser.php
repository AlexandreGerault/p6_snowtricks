<?php

declare(strict_types=1);

namespace App\Tests\Helpers\Security;

use App\Security\Entity\User;
use App\Security\Infrastructure\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FetchUser
{
    private function __construct(private readonly ContainerInterface $container)
    {
    }

    public static function new(ContainerInterface $container): static
    {
        return new self($container);
    }

    public function fetchUserByMail(string $email): User
    {
        $userRepository = $this->container->get(UserRepository::class);

        return $userRepository->findOneBy(['email' => $email]);
    }
}
