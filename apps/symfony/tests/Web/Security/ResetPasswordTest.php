<?php

declare(strict_types=1);

namespace App\Tests\Web\Security;

use App\Security\Infrastructure\DataFixtures\UserFixture;
use App\Tests\Web\WebTestCase;
use Exception;
use PHPUnit\Framework\Assert;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ResetPasswordTest extends WebTestCase
{
    use MailerAssertionsTrait;

    /** @throws Exception */
    public function testItSendsAResetRequestWhenEmailExists(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/nouveau-mot-de-passe');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Demande de réinitialisation de votre mot de passe', $crawler->html());

        $client->submitForm('Envoyer', [
            'ask_password_reset[email]' => UserFixture::ADMIN_MAIL,
        ]);

        /** @var ?TemplatedEmail $email */
        $email = $this->getMailerMessage();

        if (is_null($email)) {
            Assert::fail('No email was sent');
        }

        if (!is_string($htmlBody = $email->getHtmlBody())) {
            Assert::fail('Email body is not a string');
        }

        $link = $this->extractLinkFromEmail($htmlBody);

        $this->assertEmailCount(1);
        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();

        $this->assertStringContainsString('Un email de réinitialisation de mot de passe vous a été envoyé !', $crawler->html());
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
        $this->assertResponseRedirects('/');
        $client->followRedirect();

        $client->request('GET', '/connexion');
        $client->submitForm('Se connecter', [
            '_username' => UserFixture::ADMIN_NAME,
            '_password' => 'new_password',
        ]);
        $security = static::getContainer()->get(AuthorizationCheckerInterface::class);
        $this->assertTrue($security?->isGranted('IS_AUTHENTICATED_FULLY'));
    }

    public function testItDoesNotSendTheMailWhenEmailDoesNotExist(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/nouveau-mot-de-passe');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Demande de réinitialisation de votre mot de passe', $crawler->html());

        $client->submitForm('Envoyer', [
            'ask_password_reset[email]' => 'wrong@email.fr',
        ]);
        $this->assertEmailCount(0);
        $this->assertResponseRedirects();
    }

    public function testItDisplaysAnErrorIfTheResetTokenIsInvalid(): void
    {
        $client = static::createClient();
        $client->request('GET', '/nouveau-mot-de-passe/reinitialisation/0utVQfNb7ZEGtWrOeNJIXbBjvjg8JpIb6R7Vo666');
        $this->assertResponseRedirects('/nouveau-mot-de-passe/reinitialisation');
        $crawler = $client->followRedirect();
        $this->assertResponseRedirects('/nouveau-mot-de-passe');
        $crawler = $client->followRedirect();
        $this->assertStringContainsString(
            'Un problème est survenu lors de la validation de votre demande de réinitialisation de mot de passe',
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
