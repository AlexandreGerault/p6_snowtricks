<?php

declare(strict_types=1);

namespace App\Trick\Core;

class Video
{
    public function __construct(public readonly string $url)
    {
    }
}
