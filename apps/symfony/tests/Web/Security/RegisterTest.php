<?php

declare(strict_types=1);

namespace App\Tests\Web\Security;

use App\Security\Infrastructure\DataFixtures\UserFixture;
use App\Security\Infrastructure\Entity\User;
use App\Security\Infrastructure\Repository\UserRepository;
use App\Tests\Web\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class RegisterTest extends WebTestCase
{
    public function testItCanRegisterAUserAndSendAConfirmationEmailWithActivationToken(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/inscription');
        $this->assertResponseIsSuccessful();

        $client->submitForm("S'inscrire", [
            'register[username]' => 'Alexandre Gérault',
            'register[email]' => 'alexandre-gerault@email.fr',
            'register[password][first]' => 'password',
            'register[password][second]' => 'password',
        ]);
        $this->assertResponseRedirects();

        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get(UserRepository::class);
        $createdUser = $userRepository->findOneBy(['email' => 'alexandre-gerault@email.fr']);
        $this->assertInstanceOf(User::class, $createdUser);
        $this->assertEquals('Alexandre Gérault', $createdUser->username());
        $this->assertFalse($createdUser->isActive());
        $this->assertNotEquals('password', $createdUser->getPassword());

        $message = $this->getMailerMessage();
        $this->assertEmailCount(1);
        $this->assertEmailHtmlBodyContains($message, '/confirmer/'.$createdUser->activationToken()->getToken());
    }

    public function testItCannotRegisterAUserWithAnEmailAlreadyInUse(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/inscription');
        $this->assertResponseIsSuccessful();

        $crawler = $client->submitForm("S'inscrire", [
            'register[username]' => UserFixture::ADMIN_NAME,
            'register[email]' => UserFixture::ADMIN_MAIL,
            'register[password][first]' => UserFixture::PASSWORD,
            'register[password][second]' => UserFixture::PASSWORD,
        ]);
        $this->assertResponseIsSuccessful();

        $this->assertStringContainsString(
            UserFixture::ADMIN_MAIL.' est déjà utilisé comme adresse mail.',
            $crawler->html()
        );
    }
}
