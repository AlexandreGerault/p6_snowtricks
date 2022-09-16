<?php

namespace App\Security\UserInterface\UseCases\AskPasswordReset;

use App\Security\Core\UseCases\AskPasswordReset\AskPasswordReset;
use App\Security\Core\UseCases\AskPasswordReset\AskPasswordResetInputData;
use App\Security\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AskPasswordResetController extends AbstractController
{
    public function __construct(private readonly AskPasswordReset $askPasswordReset, private readonly UrlGeneratorInterface $generator)
    {
    }

    #[Route(path: '/nouveau-mot-de-passe', name: 'ask_password_reset')]
    public function __invoke(Request $request, UserRepository $userRepository, EntityManagerInterface $em, RequestStack $requestStack): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }

        $dto = new AskPasswordResetDTO();
        $form = $this->createForm(AskPasswordResetType::class, $dto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var AskPasswordResetDTO $dto */
            $dto = $form->getData();
            $email = $dto->email;

            $input = new AskPasswordResetInputData($email);
            $presenter = new AskPasswordResetWebPresenter(
                $this->generator,
                $requestStack->getSession()->getBag('flashes')
            );
            $this->askPasswordReset->executes($input, $presenter);

            return $presenter->response();
        }

        return $this->renderForm('security/ask-password-reset.html.twig', [
            'form' => $form,
        ]);
    }
}
