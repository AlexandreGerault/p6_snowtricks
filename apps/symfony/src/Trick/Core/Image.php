<?php

declare(strict_types=1);

namespace App\Trick\Core;

class Image
{
    public function __construct(public readonly string $path, public readonly string $description)
    {
    }

    public function equals(Image $b): bool
    {
        return $this->path === $b->path && $this->description === $b->description;
    }

    public function __toString(): string
    {
        return "{$this->path} - {$this->description}";
    }
}
