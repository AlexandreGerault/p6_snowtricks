<?php

namespace App\Trick\UserInterface\UseCases\ShowTrick;

use App\Security\Infrastructure\Entity\User;
use App\Trick\Core\UseCases\CommentTrick\CommentTrick;
use App\Trick\Infrastructure\Entity\Trick;
use App\Trick\UserInterface\UseCases\CommentTrick\CommentTrickDTO;
use App\Trick\UserInterface\UseCases\CommentTrick\CommentTrickType;
use App\Trick\UserInterface\UseCases\CommentTrick\CommentTrickWebPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ShowTrickWebController extends AbstractController
{
    public function __construct(private readonly CommentTrick $commentTrick, private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    #[Route('/figure/{slug}', name: 'show_trick')]
    public function __invoke(Trick $trick, Request $request, RequestStack $requestStack): Response
    {
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

        return $this->renderForm('trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }
}
