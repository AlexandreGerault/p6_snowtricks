<?php

namespace App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews;

class GetPaginatedTrickOverviews
{
    public function __construct(private readonly GetPaginatedTrickOverviewsQuery $query)
    {
    }

    public function executes(GetPaginatedTrickOverviewsInputData $input, GetPaginatedTrickOverviewsPresenter $output): void
    {
        $tricks = $this->query->run($input->perPage, $input->page);

        $output->presents($tricks);
    }
}
