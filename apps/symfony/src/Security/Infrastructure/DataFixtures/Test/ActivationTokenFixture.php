<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\DataFixtures\Test;

use App\Security\Infrastructure\Entity\ActivationToken;
use App\Security\Infrastructure\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ActivationTokenFixture extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public const ACTIVATION_TOKEN = '12345';

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var User $inactiveUser */
        $inactiveUser = $this->getReference(UserFixture::INACTIVE_NAME);

        $token = (new ActivationToken())
            ->setToken(self::ACTIVATION_TOKEN)
            ->setUser($inactiveUser);

        $manager->persist($token);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['test'];
    }
}
