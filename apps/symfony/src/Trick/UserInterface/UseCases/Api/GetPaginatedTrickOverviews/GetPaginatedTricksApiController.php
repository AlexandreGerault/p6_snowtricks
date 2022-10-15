<?php

namespace App\Trick\UserInterface\UseCases\Api\GetPaginatedTrickOverviews;

use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\GetPaginatedTrickOverviews;
use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\GetPaginatedTrickOverviewsInputData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Twig\Environment;

class GetPaginatedTricksApiController extends AbstractController
{
    public function __construct(
        private readonly GetPaginatedTrickOverviews $getPaginatedTricks,
        private readonly GetPaginatedTrickApiPresenter $presenter
    ) {
    }

    #[Route('/api/tricks', name: 'api_tricks_list')]
    public function __invoke(Request $request): Response
    {
        $input = new GetPaginatedTrickOverviewsInputData(
            perPage: $request->query->getInt('limit', 10),
            page: 1,
        );

        $this->getPaginatedTricks->executes($input, $this->presenter);

        return $this->presenter->response();
    }
}
