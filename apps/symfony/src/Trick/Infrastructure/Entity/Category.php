<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: '`categories`')]
class Category
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $uuid;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $name;

    public function name(): string
    {
        return $this->name;
    }

    public function uuid(): Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): Category
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function setName(string $name): Category
    {
        $this->name = $name;
        return $this;
    }
}
