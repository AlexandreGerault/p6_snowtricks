<?php

namespace App\Security\UserInterface\UseCases\AskPasswordReset;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AskPasswordRequestMail extends TemplatedEmail
{
    public function __construct(string $token, string $receiver, UrlGeneratorInterface $urlGenerator, Headers $headers = null, AbstractPart $body = null)
    {
        parent::__construct($headers, $body);

        $link = $urlGenerator->generate(
            'change_password',
            ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $this
            ->subject('Réinitialisation de mot passe')
            ->from('no-replay@snowtricks')
            ->to($receiver)
            ->textTemplate('security/emails/ask-password-reset.txt.twig')
            ->htmlTemplate('security/emails/ask-password-reset.html.twig')
            ->context([
                'link' => $link,
            ]);
    }
}
