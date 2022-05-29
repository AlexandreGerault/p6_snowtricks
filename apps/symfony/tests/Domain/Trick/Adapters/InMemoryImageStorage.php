<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\Adapters;

use App\Trick\Core\ImageStorage;

class InMemoryImageStorage implements ImageStorage
{
    private array $images = [];

    public function findAll(): array
    {
        return $this->images;
    }

    public function save(string $path): string
    {
        $this->images[] = $path;

        return $path;
    }
}
