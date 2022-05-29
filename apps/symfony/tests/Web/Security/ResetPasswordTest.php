<?php

declare(strict_types=1);

namespace App\Tests\Web\Security;

use App\Security\DataFixtures\UserFixture;
use App\Test\Web\WebTestCase;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ResetPasswordTest extends WebTestCase
{
    use MailerAssertionsTrait;

    /** @throws Exception */
    public function test_it_sends_a_reset_request_when_email_exists(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/nouveau-mot-de-passe');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Demande de réinitialisation de votre mot de passe', $crawler->html());

        $client->submitForm('Envoyer', [
            'reset_password_request_form[email]' => UserFixture::ADMIN_MAIL,
        ]);

        /** @var TemplatedEmail $email */
        $email = $this->getMailerMessage();
        if (!is_string($htmlBody = $email->getHtmlBody())) {
            throw new Exception('Email body is not a string');
        }
        $link = $this->extractLinkFromEmail($htmlBody);

        $this->assertEmailCount(1);
        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();

        $this->assertStringContainsString('Mail de réinitialisation de mot passe envoyé', $crawler->html());
        $this->assertEmailTextBodyContains($email, $link);
        $this->assertEmailHtmlBodyContains($email, $link);

        $client->request('GET', $link);
        $this->assertResponseRedirects();

        $crawler = $client->followRedirect();
        $this->assertStringContainsString('Réinitialiser votre mot de passe', $crawler->html());

        $client->submitForm('Réinitialiser', [
            'change_password_form[plainPassword][first]' => 'new_password',
            'change_password_form[plainPassword][second]' => 'new_password',
        ]);
        $this->assertResponseRedirects("/");
        $client->followRedirect();

        $client->request('GET', '/connexion');
        $client->submitForm('Se connecter', [
            '_username' => UserFixture::ADMIN_NAME,
            '_password' => 'new_password',
        ]);
        $security = static::getContainer()->get(AuthorizationCheckerInterface::class);
        $this->assertTrue($security?->isGranted('IS_AUTHENTICATED_FULLY'));
    }

    public function test_it_does_not_send_the_mail_when_email_does_not_exist(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/nouveau-mot-de-passe');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Demande de réinitialisation de votre mot de passe', $crawler->html());

        $client->submitForm('Envoyer', [
            'reset_password_request_form[email]' => "wrong@email.fr",
        ]);
        $this->assertEmailCount(0);
        $this->assertResponseRedirects();
    }

    public function test_it_displays_an_error_if_the_reset_token_is_invalid(): void
    {
        $client = static::createClient();
        $client->request('GET', '/nouveau-mot-de-passe/reinitialisation/0utVQfNb7ZEGtWrOeNJIXbBjvjg8JpIb6R7Vo666');
        $this->assertResponseRedirects('/nouveau-mot-de-passe/reinitialisation');
        $crawler = $client->followRedirect();
        $this->assertResponseRedirects('/nouveau-mot-de-passe');
        $crawler = $client->followRedirect();
        $this->assertStringContainsString(
            "Un problème est survenu lors de la validation de votre demande de réinitialisation de mot de passe",
            $crawler->html()
        );
    }

    private function extractLinkFromEmail(string $email): string
    {
        $matches = [];
        preg_match('/href="(.*)"/', $email, $matches);
        return $matches[1];
    }
}
