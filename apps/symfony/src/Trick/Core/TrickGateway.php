<?php

declare(strict_types=1);

namespace App\Trick\Core;

use Symfony\Component\Uid\AbstractUid;

interface TrickGateway
{
    public function save(Trick $trick): void;

    /** @return array<Trick> */
    public function findAll(): array;

    public function get(AbstractUid $trickId): Trick;

    public function delete(AbstractUid $trickId): bool;
}
