<?php

declare(strict_types=1);

namespace App\Trick\Core;

use Symfony\Component\Uid\AbstractUid;

class Trick
{
    /**
     * @param array<Image> $images
     * @param array<Video> $videos
     */
    public function __construct(
        private readonly string $name,
        private readonly string $description,
        private readonly AbstractUid $category,
        private readonly string $slug,
        private readonly array $images,
        private readonly array $videos,
    ) {
        if (count($images) === 0) {
            throw new \InvalidArgumentException('Trick must have at least one image');
        }

        if (count($videos) === 0) {
            throw new \InvalidArgumentException('Trick must have at least one video');
        }
    }

    public static function create(
        string $name,
        string $description,
        AbstractUid $category,
        array $images,
        array $videos
    ): Trick {
        return new self($name, $description, $category, Slugger::slugify($name), $images, $videos);
    }

    public function snapshot(): TrickSnapshot
    {
        return new TrickSnapshot(
            $this->name,
            $this->description,
            $this->category,
            $this->slug,
            $this->images,
            $this->videos
        );
    }
}
