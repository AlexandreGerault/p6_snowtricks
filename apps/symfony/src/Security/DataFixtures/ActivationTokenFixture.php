<?php

declare(strict_types=1);

namespace App\Security\DataFixtures;

use App\Security\Entity\ActivationToken;
use App\Security\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ActivationTokenFixture extends Fixture implements DependentFixtureInterface
{
    public const ACTIVATION_TOKEN = "12345";

    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
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
            UserFixture::class
        ];
    }
}
