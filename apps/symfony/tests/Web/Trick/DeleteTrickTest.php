<?php

declare(strict_types=1);

namespace App\Tests\Web\Trick;

use App\Security\Infrastructure\DataFixtures\Test\UserFixture;
use App\Tests\Helpers\Security\FetchUser;
use App\Tests\Helpers\Trick\FindCategory;
use App\Tests\Helpers\Trick\FindTrick;
use App\Tests\Web\WebTestCase;
use App\Trick\Infrastructure\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\AbstractUid;

class DeleteTrickTest extends WebTestCase
{
    use FindCategory;
    use FindTrick;

    public function testItCanDeleteATrick(): void
    {
        $client = static::createClient();

        $client->loginUser(FetchUser::new($client->getContainer())->fetchUserByMail(UserFixture::ADMIN_MAIL));

        /** @var AbstractUid $id */
        $id = $this->getTrickId('Trick 2');
        $client->request(Request::METHOD_POST, "/figure/supprimer/{$id->toRfc4122()}");

        $this->assertResponseRedirects('/');

        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('div.flash-success')->count());
        $crawler->filter('div.flash-success')->each(function ($node) {
            $this->assertStringContainsString('La figure a bien été supprimée.', $node->text());
        });

        $trick = $this->getContainer()->get(TrickRepository::class)->findBy(['slug' => 'trick-2']);
        $this->assertCount(0, $trick, 'Trick 2 should be deleted');
    }

    public function testItCanHandleDeleteWhenNoTrickCorresponds(): void
    {
        $client = static::createClient();

        $id = $this->getTrickId('Trick 2');
        $trick = $this->getContainer()->get(TrickRepository::class)->findOneBy(['slug' => 'trick-2']);
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $entityManager->remove($trick);
        $entityManager->flush();

        $client->loginUser(FetchUser::new($client->getContainer())->fetchUserByMail(UserFixture::ADMIN_MAIL));

        $client->request(Request::METHOD_POST, "/figure/supprimer/{$id->toRfc4122()}");

        $this->assertResponseRedirects('/');

        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('div.flash-error')->count());
        $crawler->filter('div.flash-error')->each(function ($node) {
            $this->assertStringContainsString('La figure n\a pas pu être supprimée.', $node->text());
        });
    }
}
