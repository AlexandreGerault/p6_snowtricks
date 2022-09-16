<?php

namespace App\Security\UserInterface\UseCases\AskPasswordReset;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;

class AskPasswordRequestMail extends Email
{
    public function __construct(string $token, string $receiver, Headers $headers = null, AbstractPart $body = null)
    {
        parent::__construct($headers, $body);
        $this
            ->subject('Réinitialisation de mot passe')
            ->from('no-replay@snowtricks')
            ->to($receiver)
            ->text(<<<TXT
Veuillez réinitialiser votre mot de passe en cliquant sur le lien {$token}. Si vous n'êtes pas à l'origine de cette demande, vous pouvez l'ignorer.
TXT
            )
            ->html(<<<HTML
<p>Veuillez réinitialiser votre mot de passe en cliquant sur le lien <a href="{$token}">{$token}</a>. Si vous n'êtes pas à l'origine de cette demande, vous pouvez l'ignorer.</p>
HTML
            );
    }
}
