<?php

declare(strict_types=1);

namespace App\Tests\Web\Security;

use App\Security\DataFixtures\UserFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class LoginTest extends WebTestCase
{
    public function test_it_can_authenticate(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/connexion');
        $this->assertResponseIsSuccessful();

        $client->submitForm("Se connecter", [
            '_username' => UserFixture::ADMIN_NAME,
            '_password' => UserFixture::PASSWORD,
        ]);

        $security = static::getContainer()->get(AuthorizationCheckerInterface::class);
        $this->assertTrue($security->isGranted('IS_AUTHENTICATED_FULLY'));
    }

    public function test_it_shows_error_when_user_provides_wrong_credentials(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/connexion');
        $this->assertResponseIsSuccessful();

        $crawler = $client->submitForm("Se connecter", [
            '_username' => "Wrong user",
            '_password' => "Wrong password",
        ]);
        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();

        $this->assertStringContainsString('Identifiants invalides.', $crawler->html());
    }
}
