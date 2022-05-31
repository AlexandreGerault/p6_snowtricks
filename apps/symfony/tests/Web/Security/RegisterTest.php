<?php

declare(strict_types=1);

namespace App\Tests\Web\Security;

use App\Security\DataFixtures\UserFixture;
use App\Security\Entity\ActivationToken;
use App\Security\Entity\User;
use App\Security\Repository\UserRepository;
use App\Tests\Web\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class RegisterTest extends WebTestCase
{
    public function test_it_can_register_a_user_and_send_a_confirmation_email_with_activation_token(): void
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
        $this->assertNotEquals("password", $createdUser->getPassword()); // check password has been hashed

        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $token = $em->createQueryBuilder()
            ->select('a')
            ->from(ActivationToken::class, 'a')
            ->innerJoin('a.user', 'u')
            ->where('u.uuid = :id')
            ->setParameter('id', $createdUser->id()->toBinary())
            ->getQuery()
            ->getOneOrNullResult();
        $this->assertInstanceOf(ActivationToken::class, $token);

        $message = $this->getMailerMessage();
        $this->assertEmailCount(1);
        $this->assertEmailHtmlBodyContains($message, "/confirmer?token={$token->getToken()}");
    }

    public function test_it_cannot_register_a_user_with_an_email_already_in_use(): void
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
            UserFixture::ADMIN_MAIL . " est déjà utilisé comme adresse mail.",
            $crawler->html()
        );
    }
}
