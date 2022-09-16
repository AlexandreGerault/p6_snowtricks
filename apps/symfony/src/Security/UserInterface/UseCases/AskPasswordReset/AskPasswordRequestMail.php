<?php

namespace App\Security\UserInterface\UseCases\AskPasswordReset;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AskPasswordRequestMail extends Email
{
    public function __construct(string $token, string $receiver, UrlGeneratorInterface $urlGenerator, Headers $headers = null, AbstractPart $body = null)
    {
        parent::__construct($headers, $body);

        $link = $urlGenerator->generate('change_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        $this
            ->subject('Réinitialisation de mot passe')
            ->from('no-replay@snowtricks')
            ->to($receiver)
            ->text(<<<TXT
Veuillez réinitialiser votre mot de passe en cliquant sur le lien {$link}. Si vous n'êtes pas à l'origine de cette demande, vous pouvez l'ignorer.
TXT
            )
            ->html(<<<HTML
<p>Veuillez réinitialiser votre mot de passe en cliquant sur le lien <a href="{$link}">Changer mon mot de passe</a>. Si vous n'êtes pas à l'origine de cette demande, vous pouvez l'ignorer.</p>
HTML
            );
    }
}
