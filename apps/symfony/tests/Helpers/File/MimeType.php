<?php

declare(strict_types=1);

namespace App\Tests\Helpers\File;

use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Mime\MimeTypesInterface;

class MimeType
{
    private static ?MimeTypes $mime = null;

    public static function getMimeTypes(): MimeTypes|MimeTypesInterface|null
    {
        if (null === self::$mime) {
            self::$mime = new MimeTypes();
        }

        return self::$mime;
    }

    public static function from(string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        return self::get($extension);
    }

    public static function get(string $extension): string
    {
        return self::getMimeTypes()->getExtensions($extension)[0] ?? 'application/octet-stream';
    }

    public static function search(string $mimeType): ?string
    {
        return self::getMimeTypes()->getExtensions($mimeType)[0] ?? null;
    }
}
