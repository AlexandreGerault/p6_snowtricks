<?php

namespace App\Security\UserInterface\UseCases\ChangePassword;

use App\Security\Core\UseCases\ChangePassword\ChangePassword;
use App\Security\Core\UseCases\ChangePassword\ChangePasswordInputData;
use App\Security\Infrastructure\Entity\PasswordResetToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ChangePasswordController extends AbstractController
{
    public function __construct(private readonly ChangePassword $changePassword, private readonly UrlGeneratorInterface $generator)
    {
    }

    #[Route(path: '/nouveau-mot-de-passe/reinitialisation/{token}', name: 'change_password')]
    public function __invoke(PasswordResetToken $token, Request $request): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }

        $dto = new ChangePasswordDTO();
        $form = $this->createForm(ChangePasswordType::class, $dto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ChangePasswordDTO $dto */
            $dto = $form->getData();
            $password = $dto->password;

            $input = new ChangePasswordInputData($token, $password);
            $presenter = new ChangePasswordWebPresenter($this->generator);

            $this->changePassword->executes($input, $presenter);

            return $presenter->response();
        }

        return $this->renderForm('security/change-password.html.twig', [
            'form' => $form,
        ]);
    }
}