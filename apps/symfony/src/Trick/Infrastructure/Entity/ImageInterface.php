<?php

namespace App\Trick\Infrastructure\Entity;

interface ImageInterface
{
    public function alt(): string;

    public function path(): string;
}