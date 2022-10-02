<?php

namespace App\Trick\Infrastructure\Entity;

use App\Security\Infrastructure\Entity\User;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity]
#[ORM\Table(name: '`trick_comments`')]
class Comment
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private AbstractUid $uuid;

    #[ORM\Column(type: Types::TEXT, length: 65535)]
    private string $content;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\JoinColumn(name: 'trick_uuid', referencedColumnName: 'uuid', nullable: false)]
    #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'comments')]
    private Trick $trick;

    #[ORM\JoinColumn(name: 'author_uuid', referencedColumnName: 'uuid', nullable: false)]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    private User $author;

    public function uuid(): AbstractUid
    {
        return $this->uuid;
    }

    public function setUuid(AbstractUid $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setTrick(Trick $trick): void
    {
        $this->trick = $trick;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
