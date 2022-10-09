<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity()]
#[ORM\Table(name: '`trick_images`')]
class Image implements ImageInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private AbstractUid $uuid;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $path;

    #[ORM\Column(type: Types::STRING)]
    private string $alt;

    #[ORM\JoinColumn(name: 'trick_uuid', referencedColumnName: 'uuid', nullable: false)]
    #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'images')]
    private Trick $trick;

    public function setUuid(AbstractUid $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function setTrick(Trick $trick): void
    {
        $this->trick = $trick;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function setAlt(string $alt): void
    {
        $this->alt = $alt;
    }

    public function alt(): string
    {
        return $this->alt;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function getFilePath(): string
    {
        return dirname($this->path);
    }

    public function getFileName(): string
    {
        return basename($this->path());
    }
}
