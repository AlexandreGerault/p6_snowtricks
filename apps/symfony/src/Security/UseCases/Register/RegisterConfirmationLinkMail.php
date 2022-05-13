<?php

declare(strict_types=1);

namespace App\Security\UseCases\Register;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;

class RegisterConfirmationLinkMail extends Email
{
    public function __construct(string $confirmLink, string $receiver, Headers $headers = null, AbstractPart $body = null)
    {
        parent::__construct($headers, $body);
        $this
            ->subject("Confirmation de l'inscription sur Snowtricks")
            ->from("no-replay@snowtricks")
            ->to($receiver)
            ->text(<<<TXT
Félicitation pour votre inscription sur Snowtricks ! Veuillez confirmer votre inscription en cliquant sur le lien {$confirmLink}
TXT
            )
            ->html(<<<HTML
<p>Félicitation pour votre inscription sur Snowtricks ! Veuillez <a href="{$confirmLink}">confirmer votre inscription</a></p>
HTML
            );
    }
}
