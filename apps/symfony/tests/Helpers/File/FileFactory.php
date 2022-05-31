<?php

declare(strict_types=1);

namespace App\Tests\Helpers\File;

class FileFactory
{
    public function create(string $name, string|int $kilobytes = 0, ?string $mimeType = null): File
    {
        if (is_string($kilobytes)) {
            return $this->createWithContent($name, $kilobytes);
        }
        $file = new File($name, tmpfile());
        $file->sizeToReport = $kilobytes * 1024;
        $file->mimeTypeToReport = $mimeType;

        return $file;
    }

    public function createWithContent(string $name, string $content): File
    {
        $tmpFile = tmpfile();

        fwrite($tmpFile, $content);

        $file = new File($name, $tmpFile);
        $file->sizeToReport = fstat($tmpFile)['size'];

        return $file;
    }

    public function image(string $name, int $width = 10, int $height = 10): File
    {
        return new File($name, $this->generateImage($width, $height, pathinfo($name, PATHINFO_EXTENSION)));
    }

    /** @return resource */
    protected function generateImage(int $width, int $height, string $extension)
    {
        $tmpFile = tmpfile();

        ob_start();

        $extension = in_array($extension, ['jpeg', 'png', 'gif', 'webp', 'wbmp', 'bmp'])
            ? strtolower($extension)
            : 'jpeg';

        $image = imagecreatetruecolor($width, $height);

        call_user_func("image{$extension}", $image);

        fwrite($tmpFile, ob_get_clean());

        return $tmpFile;
    }
}
