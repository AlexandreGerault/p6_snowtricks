<?php

declare(strict_types=1);

namespace App\Tests\Web\Security;

use App\Security\DataFixtures\ActivationTokenFixture;
use App\Security\DataFixtures\UserFixture;
use App\Security\Entity\User;
use App\Security\Infrastructure\Repository\UserRepository;
use App\Tests\Web\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class ActivateAccountTest extends WebTestCase
{
    public function testItActivatesAnAccountWhenClickingOnConfirmLink(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/confirmer?token='.ActivationTokenFixture::ACTIVATION_TOKEN);

        $this->assertResponseRedirects();

        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get(UserRepository::class);
        /** @var User $inactiveUser */
        $inactiveUser = $userRepository->findOneBy(['username' => UserFixture::INACTIVE_NAME]);

        $this->assertTrue($inactiveUser->isActive());
    }

    public function testItCannotFindAccountForGivenToken(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/confirmer?token=invalid');

        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();

        $this->assertStringContainsString("Aucun compte ne correspond à ce jeton d'activation !", $crawler->html());
    }
}
