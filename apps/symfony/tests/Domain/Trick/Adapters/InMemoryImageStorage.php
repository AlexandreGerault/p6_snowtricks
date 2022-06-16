<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\Adapters;

use App\Trick\Core\ImageStorage;
use PHPUnit\Framework\Assert;

class InMemoryImageStorage implements ImageStorage
{
    public function __construct(private array $images = [])
    {
    }

    public function findAll(): array
    {
        return $this->images;
    }

    public function save(string $path): string
    {
        $this->images[] = $path;

        return $path;
    }

    public function assertImageExists(string $path): void
    {
        Assert::assertContains($path, $this->images);
    }

    public function assertImageDoesNotExist(string $path): void
    {
        Assert::assertNotContains($path, $this->images);
    }

    public function delete($path): void
    {
        $this->images = array_filter($this->images, function ($image) use ($path) {
            return $image !== $path;
        });
    }
}
