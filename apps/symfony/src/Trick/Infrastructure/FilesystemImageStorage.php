<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure;

use App\Trick\Core\ImageStorage;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Mime\MimeTypesInterface;
use Symfony\Component\Uid\UuidV6;

class FilesystemImageStorage implements ImageStorage
{
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly MimeTypesInterface $mimeType,
        private readonly string $uploadDir
    ) {
    }

    public function findAll(): array
    {
        $finder = new Finder();
        $finder = $finder->in($this->uploadDir)->files();
        $iterator = $finder->getIterator();

        return iterator_to_array($iterator);
    }

    public function save(string $path): string
    {
        [$tmpPath, $filename] = $this->parsePath($path);
        $file = $this->findFile($tmpPath, $filename);

        $filename = $this->generateFilename($file);

        $output = $this->uploadDir . '/' . $filename;

        $this->filesystem->copy($path, $output);

        return $output;
    }

    private function findFile(string $path, string $filename): bool|SplFileInfo
    {
        $finder = new Finder();
        $finder = $finder->in($path)->name($filename)->files();

        $iterator = $finder->getIterator();
        /** @var array<string, SplFileInfo> $result */
        $result = iterator_to_array($iterator);
        return reset($result);
    }

    private function parsePath(string $path): array
    {
        $path = explode('/', $path);
        $filename = array_pop($path);
        $path = implode('/', $path);

        return [$path, $filename];
    }

    private function generateFilename(mixed $file): string
    {
        $extension = $this->mimeType->getExtensions($this->mimeType->guessMimeType($file->getRealPath()))[0];
        $uniqueName = UuidV6::generate();

        return "{$uniqueName}.{$extension}";
    }
}
