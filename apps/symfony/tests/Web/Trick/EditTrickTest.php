<?php

declare(strict_types=1);

namespace App\Tests\Web\Trick;

use App\Security\DataFixtures\UserFixture;
use App\Tests\Helpers\Security\FetchUser;
use App\Tests\Web\WebTestCase;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class EditTrickTest extends WebTestCase
{
    public function testEditTrick()
    {
        $client = static::createClient();

        $client->loginUser(FetchUser::new($client->getContainer())->fetchUserByMail(UserFixture::ADMIN_MAIL));

        $crawler = $client->request(Request::METHOD_GET, "/figure/modifier/{$this->getTrickSlug()}");

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form[name="edit_trick"]')->form();
//        $csrfTokenField = $form->get("register_trick[_token]");


    }

    private function getTrickSlug(): string
    {
        /** @var Connection $connection */
        $connection = $this->getContainer()->get(EntityManagerInterface::class)->getConnection();

        $sql = "SELECT * FROM tricks";

        try {
            $statement = $connection->prepare($sql);
        } catch (Exception $e) {
            $this->fail("Trick not found");
        }

        return $statement->executeQuery()->fetchAllAssociative()[0]['slug'];
    }
}
