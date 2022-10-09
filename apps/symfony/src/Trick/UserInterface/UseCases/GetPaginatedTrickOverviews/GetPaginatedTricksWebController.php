<?php

namespace App\Trick\UserInterface\UseCases\GetPaginatedTrickOverviews;

use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\GetPaginatedTrickOverviews;
use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\GetPaginatedTrickOverviewsInputData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class GetPaginatedTricksWebController extends AbstractController
{
    public function __construct(private readonly GetPaginatedTrickOverviews $getPaginatedTricks, private readonly Environment $twig)
    {
    }

    #[Route('/', name: 'homepage')]
    public function __invoke(Request $request): Response
    {
        $presenter = new GetPaginatedTrickWebPresenter($this->twig);

        $input = new GetPaginatedTrickOverviewsInputData(
            perPage: $request->query->getInt('limit', 9),
            page: $request->query->getInt('page', 1),
        );

        $this->getPaginatedTricks->executes($input, $presenter);

        return $presenter->response();
    }
}
