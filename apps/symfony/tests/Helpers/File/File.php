<?php

declare(strict_types=1);

namespace App\Tests\Helpers\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class File extends UploadedFile
{
    public ?string $name = null;

    /** @var resource */
    public $tempFile;

    public ?int $sizeToReport = null;

    public ?string $mimeTypeToReport = null;

    /**
     * @param resource $tempFile
     */
    public function __construct(string $name, $tempFile)
    {
        $this->name = $name;
        $this->tempFile = $tempFile;

        parent::__construct($this->tempFilePath(), $name, $this->getMimeType(), null, true);
    }

    public static function create(string $name, string|int $kilobytes = 0): static
    {
        return (new FileFactory())->create($name, $kilobytes);
    }

    public static function createWithContent(string $name, string $content): static
    {
        return (new FileFactory())->createWithContent($name, $content);
    }

    public static function image(string $name, int $width = 10, int $height = 10): static
    {
        return (new FileFactory())->image($name, $width, $height);
    }

    public function size(int $kilobytes = 0): static
    {
        $this->sizeToReport = $kilobytes * 1024;

        return $this;
    }

    public function getSize(): int
    {
        return $this->sizeToReport ?: parent::getSize();
    }

    public function mimeType(string $mimeType): static
    {
        $this->mimeTypeToReport = $mimeType;

        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mimeTypeToReport ?: MimeType::from($this->name);
    }

    protected function tempFilePath()
    {
        return stream_get_meta_data($this->tempFile)['uri'];
    }
}
