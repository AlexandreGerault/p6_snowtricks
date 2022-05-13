<?php

declare(strict_types=1);

namespace App\Security\Entity;

use App\Security\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[UniqueEntity('email')]
class User implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $uuid;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $username;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $email;

    #[ORM\Column(type: Types::STRING)]
    private string $password;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $active = false;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: ActivationToken::class, cascade: ['remove'])]
    private ActivationToken $activationToken;

    public function id(): Uuid
    {
        return $this->uuid;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function activate(): static
    {
        $this->active = true;

        return $this;
    }
}
