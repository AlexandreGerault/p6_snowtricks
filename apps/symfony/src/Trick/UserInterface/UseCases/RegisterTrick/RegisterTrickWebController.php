<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\RegisterTrick;

use App\Trick\Core\UseCases\RegisterTrick\RegisterTrick;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/figure/ajouter', name: 'register_trick')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class RegisterTrickWebController extends AbstractController
{
    public function __construct(
        private readonly RegisterTrick $registerTrick,
        private readonly UrlGeneratorInterface $generator
    ) {
    }

    public function __invoke(Request $request, RequestStack $requestStack): Response
    {
        $dto = new RegisterTrickDTO();
        $form = $this->createForm(RegisterTrickType::class, $dto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $presenter = new RegisterTrickWebPresenter(
                $this->generator,
                $requestStack->getSession()->getBag('flashes')
            );
            $this->registerTrick->executes($dto->toDomainRequest(), $presenter);

            return $presenter->response();
        }

        return $this->renderForm('trick/create.html.twig', [
            'form' => $form,
        ]);
    }
}
