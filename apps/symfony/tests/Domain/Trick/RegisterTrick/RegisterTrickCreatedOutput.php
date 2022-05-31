<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\RegisterTrick;

class RegisterTrickCreatedOutput
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $categoryId,
    ) {
    }
}
