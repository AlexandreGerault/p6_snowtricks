<?php

namespace App\Trick\UserInterface\UseCases\Web\GetPaginatedTrickOverviews;

use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\GetPaginatedTrickOverviewsPresenter;
use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\PaginatedTrickOverviewsResult;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetPaginatedTrickWebPresenter implements GetPaginatedTrickOverviewsPresenter
{
    private Response $response;

    public function __construct(private readonly Environment $environment)
    {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function presents(PaginatedTrickOverviewsResult $trickOverviews): void
    {
        $content = $this->environment->render('homepage.html.twig', [
            'tricks' => $trickOverviews->trickOverviews,
            'page' => $trickOverviews->page,
            'perPage' => $trickOverviews->perPage,
            'total' => $trickOverviews->total,
        ]);

        $this->response = new Response($content);
    }

    public function response(): Response
    {
        return $this->response;
    }
}
