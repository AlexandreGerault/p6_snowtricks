<?php

declare(strict_types=1);

namespace App\Trick\Core;

use Symfony\Component\Uid\AbstractUid;

class Trick
{
    /**
     * @param Image[] $images
     * @param Video[] $videos
     *                        :thumb
     */
    public function __construct(
        private readonly AbstractUid $uuid,
        private string $name,
        private string $description,
        private AbstractUid $category,
        private readonly string $slug,
        private array $images,
        private array $videos,
    ) {
        if (0 === count($images)) {
            throw new \InvalidArgumentException('Trick must have at least one image');
        }

        if (0 === count($videos)) {
            throw new \InvalidArgumentException('Trick must have at least one video');
        }
    }

    /**
     * @param Image[] $images
     * @param Video[] $videos
     */
    public static function create(
        AbstractUid $uuid,
        string $name,
        string $description,
        AbstractUid $category,
        array $images,
        array $videos
    ): Trick {
        return new self($uuid, $name, $description, $category, Slugger::slugify($name), $images, $videos);
    }

    public function snapshot(): TrickSnapshot
    {
        return new TrickSnapshot(
            $this->uuid,
            $this->name,
            $this->description,
            $this->category,
            $this->slug,
            $this->images,
            $this->videos
        );
    }

    public function rename(string $name): void
    {
        $this->name = $name;
    }

    public function changeDescription(string $description): void
    {
        $this->description = $description;
    }

    public function changeCategory(AbstractUid $id): void
    {
        $this->category = $id;
    }

    /** @param Image[] $images */
    public function updateImages(array $images): void
    {
        if (0 === count($images)) {
            throw new \InvalidArgumentException('Trick must have at least one image');
        }

        $this->images = $images;
    }

    /** @param Video[] $videos */
    public function updateVideos(array $videos): void
    {
        if (0 === count($videos)) {
            throw new \InvalidArgumentException('Trick must have at least one image');
        }

        $this->videos = $videos;
    }
}
