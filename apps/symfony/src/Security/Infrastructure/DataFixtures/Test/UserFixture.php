<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture implements FixtureGroupInterface
{
    public const ADMIN_NAME = 'Admin';
    public const ADMIN_MAIL = 'admin@snowtricks.fr';

    public const INACTIVE_NAME = 'Inactive';
    public const INACTIVE_MAIL = 'inactive@snowtricks.fr';

    public const PASSWORD = 'password';

    public function __construct(private readonly UserFactory $userFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = $this->userFactory->active()->create(self::ADMIN_NAME, self::ADMIN_MAIL, self::PASSWORD);
        $inactive = $this->userFactory->inactive()->create(self::INACTIVE_NAME, self::INACTIVE_MAIL, self::PASSWORD);

        $manager->persist($admin);
        $manager->persist($inactive);

        $manager->flush();

        $this->addReference(self::INACTIVE_NAME, $inactive);
    }

    public static function getGroups(): array
    {
        return ['test'];
    }
}
