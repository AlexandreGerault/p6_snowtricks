<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Entity;

use App\Security\Infrastructure\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[UniqueEntity('email')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
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
    private ?ActivationToken $activationToken;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: PasswordResetToken::class, cascade: ['remove'])]
    private ?PasswordResetToken $passwordResetToken;

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

    public function getRoles(): array
    {
        return [];
    }

    public function activationToken(): ?ActivationToken
    {
        return $this->activationToken;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function setActivationToken(?ActivationToken $activationToken): User
    {
        $this->activationToken = $activationToken;

        return $this;
    }

    public function setPasswordResetToken(PasswordResetToken $passwordResetToken): User
    {
        $this->passwordResetToken = $passwordResetToken;

        return $this;
    }

    public function passwordResetToken(): ?PasswordResetToken
    {
        return $this->passwordResetToken;
    }
}
