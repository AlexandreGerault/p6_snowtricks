<?php

namespace App\Tests\Helpers\Trick;

use App\Trick\Infrastructure\Entity\Trick;
use App\Trick\Infrastructure\TrickRepository;
use Exception;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\AbstractUid;

trait FindTrick
{
    private function getTrickSlug(string $name): string
    {
        $trick = $this->getTrickByName($name);

        return $trick->slug();
    }

    private function getTrickId(string $name): AbstractUid
    {
        $trick = $this->getTrickByName($name);

        return $trick->uuid();
    }

    /**
     * @return Trick|mixed|object|null
     */
    private function getTrickByName(?string $name): mixed
    {
        try {
            /** @var TrickRepository $repository */
            $repository = $this->getContainer()->get(TrickRepository::class);
        } catch (Exception) {
            Assert::fail('TrickRepository not found');
        }

        $trick = $repository->findOneBy(['name' => $name]);

        if (!$trick) {
            Assert::fail("Trick {$name} not found");
        }

        return $trick;
    }
}
