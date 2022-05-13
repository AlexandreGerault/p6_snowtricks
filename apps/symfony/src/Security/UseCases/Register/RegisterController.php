<?php

declare(strict_types=1);

namespace App\Security\UseCases\Register;

use App\Security\Entity\ActivationToken;
use App\Security\Entity\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegisterController extends AbstractController
{
    public function __construct(
        private readonly UserFactory $factory,
        private readonly EntityManagerInterface $entityManager,
        private readonly MailerInterface $mailer
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route(path: '/inscription', name: 'app_register')]
    public function __invoke(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(RegisterType::class, new RegisterFormModel());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var RegisterFormModel $data */
            $data = $form->getData();

            $user = $this->factory->create($data->username, $data->email, $data->password);
            $user->setPassword($hasher->hashPassword($user, $data->password));

            $activationToken = new ActivationToken();
            $activationToken->setToken("token");
            $activationToken->setUser($user);

            $this->entityManager->persist($user);
            $this->entityManager->persist($activationToken);
            $this->entityManager->flush();

            $this->sendConfirmationLink(
                $user->email(),
                $this->generateUrl('app_activate_account', ['token' => $activationToken->getToken()], UrlGeneratorInterface::ABSOLUTE_URL)
            );

            return $this->redirect('/inscription');
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
