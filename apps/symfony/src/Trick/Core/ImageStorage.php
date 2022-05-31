<?php

declare(strict_types=1);

namespace App\Trick\Core;

interface ImageStorage
{
    /** @return array<string> */
    public function findAll(): array;

    /** Save an image from a given path and return the generated image path */
    public function save(string $path): string;
}
