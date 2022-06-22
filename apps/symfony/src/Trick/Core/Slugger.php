<?php

declare(strict_types=1);

namespace App\Trick\Core;

class Slugger
{
    public static function slugify(string $name): string
    {
        $name = iconv('UTF-8', 'ASCII//TRANSLIT', $name);

        if (!$name) {
            throw new \RuntimeException('Unable to normalize accentuated characters');
        }

        /** @var string $name */
        $name = preg_replace('/[^a-zA-Z0-9]/', '-', $name);

        return \strtolower($name);
    }
}
