<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure\Entity;

use App\Trick\Infrastructure\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[ORM\Table(name: '`tricks`')]
class Trick
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    private Uuid $uuid;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    private string $slug;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\JoinColumn(name: "category_uuid", referencedColumnName: "uuid")]
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "tricks")]
    private Category $category;

    #[ORM\OneToMany(mappedBy: "trick", targetEntity: Image::class, cascade: ["persist", "remove"])]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: "trick", targetEntity: Video::class, cascade: ["persist", "remove"])]
    private Collection $videos;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    public function setUuid(Uuid $uuid): void
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

    /** @param Collection<int, Image> $images */
    public function setImages(Collection $images): static
    {
        $this->images = $images;
    }

    /**
     * @param Collection<int, Video> $videos
     * @return Trick
     */
    public function setVideos(Collection $videos): Trick
    {
        $this->videos = $videos;

        return $this;
    }

    public function addImage(\App\Trick\Core\Image $image): void
    {
        $new = new Image();
        $new->setPath($image->path);
        $new->setAlt($image->description);
        $this->images->add($new);
    }

    public function addVideo(\App\Trick\Core\Video $video): void
    {
        $new = new Video();
        $new->setUrl($video->url);
        $this->videos->add($new);
    }
}
