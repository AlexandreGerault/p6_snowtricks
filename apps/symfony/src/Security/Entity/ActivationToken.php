<?php

declare(strict_types=1);

namespace App\Security\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
#[ORM\Table(name: '`activation_tokens`')]
class ActivationToken
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $uuid;

    #[ORM\OneToOne(inversedBy: 'activationToken', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'uuid')]
    private ?User $user;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $token;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
