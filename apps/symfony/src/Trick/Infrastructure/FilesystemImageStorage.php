<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure;

use App\Trick\Core\ImageStorage;
use Symfony\Component\Filesystem\Filesystem;

class FilesystemImageStorage implements ImageStorage
{
    public function __construct(private readonly Filesystem $filesystem)
    {
    }

    public function findAll(): array
    {
        return [0];
    }

    public function save(string $path): string
    {
        return "";
    }
}
