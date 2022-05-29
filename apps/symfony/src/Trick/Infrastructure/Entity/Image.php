<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
#[ORM\Table(name: '`trick_images`')]
class Image
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $uuid;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $path;

    #[ORM\Column(type: Types::STRING)]
    private string $alt;

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function setAlt(string $alt): void
    {
        $this->alt = $alt;
    }
}
