<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\Api\GetPaginatedTrickOverviews;

use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\TrickOverview;

class TrickOverviewResource
{
    public readonly string $id;
    public readonly string $name;
    public readonly string $slug;
    public readonly string $category;
    public readonly string $image;

    public function __construct(
        TrickOverview $paginatedTrickOverviewsResult,
        public readonly string $url,
        public readonly ?string $editUrl = null,
        public readonly ?string $deleteUrl = null,
    ) {
        $this->id = $paginatedTrickOverviewsResult->id;
        $this->name = $paginatedTrickOverviewsResult->name;
        $this->slug = $paginatedTrickOverviewsResult->slug;
        $this->category = $paginatedTrickOverviewsResult->categoryName;
        $this->image = $paginatedTrickOverviewsResult->thumbnailUrl;
    }
}
