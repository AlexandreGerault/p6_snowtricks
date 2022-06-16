<?php

namespace App\Tests\Helpers\Trick;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

trait FindTrick
{
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