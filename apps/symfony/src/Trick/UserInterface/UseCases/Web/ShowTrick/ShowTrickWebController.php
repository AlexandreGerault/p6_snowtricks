<?php

namespace App\Trick\UserInterface\UseCases\Web\ShowTrick;

use App\Security\Infrastructure\Entity\User;
use App\Trick\Core\UseCases\Commands\CommentTrick\CommentTrick;
use App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments\GetTrickWithPaginatedComments;
use App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments\GetTrickWithPaginatedCommentsInputData;
use App\Trick\Infrastructure\Entity\Trick;
use App\Trick\UserInterface\UseCases\Web\CommentTrick\CommentTrickDTO;
use App\Trick\UserInterface\UseCases\Web\CommentTrick\CommentTrickType;
use App\Trick\UserInterface\UseCases\Web\CommentTrick\CommentTrickWebPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class ShowTrickWebController extends AbstractController
{
    public function __construct(
        private readonly CommentTrick $commentTrick,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly Security $security,
        private readonly GetTrickWithPaginatedComments $getTrickWithPaginatedComments,
        private readonly Environment $environment,
    ) {
    }

    #[Route('/figure/{slug}', name: 'show_trick')]
    public function __invoke(Trick $trick, Request $request, RequestStack $requestStack): Response
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->response($trick, $request);
        }

        /** @var User $user */
        $user = $this->getUser();

        $dto = new CommentTrickDTO($trick, $user);
        $form = $this->createForm(CommentTrickType::class, $dto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $presenter = new CommentTrickWebPresenter(
                $this->urlGenerator->generate('show_trick', ['slug' => $trick->slug()]),
                $requestStack->getSession()->getBag('flashes')
            );
            $this->commentTrick->executes($dto->toDomainRequest(), $presenter);

            return $presenter->response();
        }

        return $this->response($trick, $request, $form);
    }

    private function response(Trick $trick, Request $request, ?FormInterface $form = null): Response
    {
        $input = new GetTrickWithPaginatedCommentsInputData(
            $trick->slug(),
            $request->query->getInt('per_page', 10),
            $request->query->getInt('page', 1),
        );

        $presenter = $form
            ? new ShowTrickWebPresenter($this->environment, $form)
            : new ShowTrickWebPresenter($this->environment);

        $this->getTrickWithPaginatedComments->executes($input, $presenter);

        return $presenter->response();
    }
}
