<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity()]
#[ORM\Table(name: '`trick_videos`')]
class Video
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private AbstractUid $uuid;

    #[ORM\Column(type: Types::STRING)]
    private string $url;

    #[ORM\JoinColumn(name: 'trick_uuid', referencedColumnName: 'uuid', nullable: false)]
    #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'videos')]
    private Trick $trick;

    public function setTrick(Trick $trick): void
    {
        $this->trick = $trick;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function url(): string
    {
        return $this->url;
    }
}
