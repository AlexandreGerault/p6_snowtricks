<?php

declare(strict_types=1);

namespace App\Trick\Core;

interface TrickGateway
{
    public function save(Trick $trick): void;

    /** @return array<Trick> */
    public function findAll(): array;
}
