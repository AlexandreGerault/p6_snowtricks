<?php

declare(strict_types=1);

namespace App\Trick\Core\UseCases\EditTrick;

class EditTrickInputData
{
    /**
     * @param array<array{path: string, alt: string}> $images
     * @param string[]                                $videos
     */
    public function __construct(
        public readonly string $trickId,
        public readonly string $name,
        public readonly string $description,
        public readonly string $categoryId,
        public readonly array $images,
        public readonly array $videos,
    ) {
    }
}
