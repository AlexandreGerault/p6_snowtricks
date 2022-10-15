<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\Api\GetPaginatedTrickOverviews;

use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\TrickOverview;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class TrickOverviewResourceFactory
{
    public function __construct(private Security $security, private UrlGeneratorInterface $generator)
    {
    }

    /**
     * @param array<TrickOverview> $trickOverviews
     * @return array<TrickOverviewResource>
     */
    public function createCollection(array $trickOverviews): array
    {
        return array_map(
            fn ($trickOverview) => $this->create($trickOverview),
            $trickOverviews
        );
    }

    public function create(TrickOverview $trickOverview): TrickOverviewResource
    {
        return new TrickOverviewResource(
            $trickOverview,
            $this->url($trickOverview),
            $this->editUrl($trickOverview),
            $this->deleteUrl($trickOverview),
        );
    }

    private function url(TrickOverview $trickOverview): ?string
    {
        if ($this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->generator->generate('show_trick', ['slug' => $trickOverview->slug]);
        }

        return null;
    }

    private function editUrl(TrickOverview $trickOverview): ?string
    {
        if ($this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->generator->generate('edit_trick', ['slug' => $trickOverview->slug]);
        }

        return null;
    }

    private function deleteUrl(TrickOverview $trickOverview): ?string
    {
        if ($this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->generator->generate('delete_trick', ['id' => $trickOverview->id]);
        }

        return null;
    }
}
