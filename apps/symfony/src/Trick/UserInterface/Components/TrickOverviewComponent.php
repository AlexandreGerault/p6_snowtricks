<?php

namespace App\Trick\UserInterface\Components;

use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\TrickOverview;
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