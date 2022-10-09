<?php

namespace App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments;

class GetTrickWithPaginatedComments
{
    public function __construct(private readonly GetTrickWithPaginatedCommentsQuery $query)
    {
    }

    public function executes(GetTrickWithPaginatedCommentsInputData $input, GetTrickWithPaginatedCommentsPresenter $output): void
    {
        $tricks = $this->query->run($input->slug, $input->perPage, $input->page);

        $output->presents($tricks);
    }
}
