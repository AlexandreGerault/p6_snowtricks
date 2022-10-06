<?php

namespace App\Trick\UserInterface\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('trick-overview')]
class TrickOverviewComponent
{
    public string $title;
    public string $slug;
    public string $category;
    public string $image;
    public int $commentsCount;
}
