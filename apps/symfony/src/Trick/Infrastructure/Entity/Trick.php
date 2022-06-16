<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure\Entity;

use App\Trick\Infrastructure\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[ORM\Table(name: '`tricks`')]
class Trick
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    private AbstractUid $uuid;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $name;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $slug;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\JoinColumn(name: "category_uuid", referencedColumnName: "uuid", nullable: false)]
    #[ORM\ManyToOne(targetEntity: Category::class)]
    private Category $category;

    /** @var Collection<int, Image> */
    #[ORM\OneToMany(mappedBy: "trick", targetEntity: Image::class, cascade: ["persist", "remove"])]
    private Collection $images;

    /** @var Collection<int, Video> */
    #[ORM\OneToMany(mappedBy: "trick", targetEntity: Video::class, cascade: ["persist", "remove"])]
    private Collection $videos;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    public function setUuid(AbstractUid $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function addImage(\App\Trick\Core\Image $image): void
    {
        $new = new Image();
        $new->setTrick($this);
        $new->setPath($image->path);
        $new->setAlt($image->description);
        $this->images->add($new);
    }

    public function addVideo(\App\Trick\Core\Video $video): void
    {
        $new = new Video();
        $new->setTrick($this);
        $new->setUrl($video->url);
        $this->videos->add($new);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function category(): Category
    {
        return $this->category;
    }

    /** @return Collection<int, Image> */
    public function images(): Collection
    {
        return $this->images;
    }

    /** @return Collection<int, Video> */
    public function videos(): Collection
    {
        return $this->videos;
    }

    public function uuid(): AbstractUid
    {
        return $this->uuid;
    }

    public function slug(): string
    {
        return $this->slug;
    }
}
