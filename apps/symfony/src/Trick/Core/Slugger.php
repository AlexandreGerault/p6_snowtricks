<?php

declare(strict_types=1);

namespace App\Trick\Core;

class Slugger
{
    public static function slugify(string $name): string
    {
        return \strtolower(\preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
    }
}
