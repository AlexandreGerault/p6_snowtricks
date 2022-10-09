<?php

namespace App\Trick\UserInterface\UseCases\ShowTrick;

use App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments\GetTrickWithPaginatedCommentsPresenter;
use App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments\TrickWithPaginatedCommentsResult;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ShowTrickWebPresenter implements GetTrickWithPaginatedCommentsPresenter
{
    private Response $response;

    public function __construct(private readonly Environment $environment, private ?FormInterface $form = null)
    {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function presents(TrickWithPaginatedCommentsResult $trickWithPaginatedCommentsResult): void
    {
        $content = $this->form
            ? $this->environment->render('trick/show.html.twig', [
                'trick' => $trickWithPaginatedCommentsResult,
                'form' => $this->form->createView(),
            ])
            : $this->environment->render('trick/show.html.twig', [
                'trick' => $trickWithPaginatedCommentsResult,
                'comments' => $trickWithPaginatedCommentsResult->comments,
            ]);

        $this->response = new Response($content);
    }

    public function response(): Response
    {
        return $this->response;
    }
}
