<?php

declare(strict_types=1);

namespace App\Trick\Core;

class Image
{
    public function __construct(public readonly string $path, public readonly string $description)
    {
    }
}
