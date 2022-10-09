<?php

declare(strict_types=1);

namespace App\Trick\Core\UseCases\Commands\RegisterTrick;

class RegisterTrickInputData
{
    /**
     * @param array{path: string, alt: string}        $thumbnail
     * @param array<array{path: string, alt: string}> $images
     * @param array<string>                           $videos
     */
    public function __construct(
        public string $name,
        public string $description,
        public string $categoryId,
        public array $thumbnail,
        public array $images,
        public array $videos,
    ) {
    }
}
