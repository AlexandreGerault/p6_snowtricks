<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\EditTrick;

use App\Trick\Core\UseCases\EditTrick\EditTrick;
use App\Trick\Infrastructure\Entity\Trick;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class EditTrickWebController extends AbstractController
{
    public function __construct(private readonly UrlGeneratorInterface $generator, private readonly EditTrick $editTrick)
    {
    }

    #[Route('/figure/modifier/{slug}', name: 'edit_trick')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function __invoke(Trick $trick, Request $request, RequestStack $requestStack): Response
    {
        $dto = new EditTrickDTO($trick);
        $form = $this->createForm(EditTrickType::class, $dto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $presenter = new EditTrickWebPresenter(
                $this->generator,
                $requestStack->getSession()->getBag('flashes')
            );
            $this->editTrick->executes($dto->toDomainRequest(), $presenter);

            return $presenter->response();
        }

        return $this->renderForm('trick/edit.html.twig', [
            'form' => $form
        ]);
    }
}
