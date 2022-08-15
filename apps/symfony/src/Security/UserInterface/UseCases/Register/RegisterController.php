<?php

declare(strict_types=1);

namespace App\Security\UserInterface\UseCases\Register;

use App\Security\Core\UseCases\Register\Register;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    public function __construct(private Register $register) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route(path: '/inscription', name: 'app_register')]
    public function __invoke(Request $request, UserPasswordHasherInterface $hasher, RegisterWebPresenter $presenter): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }

        $dto = new RegisterDTO();
        $form = $this->createForm(RegisterType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->register->executes($dto->toDomainRequest(), $presenter);

            return $presenter->response();
        }

        return $this->render('security/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function sendConfirmationLink(string $to, string $confirmLink): void
    {
        $mail = new RegisterConfirmationLinkMail($confirmLink, $to);
        $this->mailer->send($mail);
    }
}
