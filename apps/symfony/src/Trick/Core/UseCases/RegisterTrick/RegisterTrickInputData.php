<?php

declare(strict_types=1);

namespace App\Trick\Core\UseCases\RegisterTrick;

class RegisterTrickInputData
{
    public function __construct(
        public string $name,
        public string $description,
        public string $categoryId,
        public array $images,
        public array $videos,
    ) {
    }
}
