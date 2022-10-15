<?php

declare(strict_types=1);

namespace App\Tests\Web\Security;

use App\Security\Infrastructure\DataFixtures\Test\UserFixture;
use App\Tests\Web\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class LoginTest extends WebTestCase
{
    /**
     * @throws \Exception
     */
    public function testItCanAuthenticate(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/connexion');
        $this->assertResponseIsSuccessful();

        $client->submitForm('Se connecter', [
            '_username' => UserFixture::ADMIN_NAME,
            '_password' => UserFixture::PASSWORD,
        ]);

        /** @var AuthorizationCheckerInterface $authorizationChecker */
        $security = static::getContainer()->get(AuthorizationCheckerInterface::class);
        $this->assertTrue($security?->isGranted('IS_AUTHENTICATED_FULLY'));
    }

    public function testItShowsErrorWhenUserProvidesWrongCredentials(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/connexion');
        $this->assertResponseIsSuccessful();

        $client->submitForm('Se connecter', [
            '_username' => 'Wrong user',
            '_password' => 'Wrong password',
        ]);
        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();

        $this->assertStringContainsString('Identifiants invalides.', $crawler->html());
    }

    public function testItShowsErrorWhenUserIsNotActivated(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/connexion');
        $this->assertResponseIsSuccessful();

        $client->submitForm('Se connecter', [
            '_username' => UserFixture::INACTIVE_NAME,
            '_password' => UserFixture::PASSWORD,
        ]);
        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();

        $this->assertStringContainsString("Votre compte utilisateur n'a pas été activé.", $crawler->html());
    }
}
