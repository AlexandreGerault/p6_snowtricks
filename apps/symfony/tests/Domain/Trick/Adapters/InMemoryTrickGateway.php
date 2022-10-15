<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\Adapters;

use App\Trick\Core\Trick;
use App\Trick\Core\TrickGateway;
use Exception;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

class InMemoryTrickGateway implements TrickGateway
{
    private bool $saved = false;
    private bool $edited = false;

    /** @param Trick[] $tricks */
    public function __construct(private array $tricks = [])
    {
    }

    public function save(Trick $trick): void
    {
        $this->saved = true;

        foreach ($this->tricks as $key => $iterationTrick) {
            if ($iterationTrick->snapshot()->uuid->equals($trick->snapshot()->uuid)) {
                $this->edited = true;
                $this->tricks[$key] = $trick;

                return;
            }
        }

        $this->tricks[] = $trick;
    }

    public function findAll(): array
    {
        return $this->tricks;
    }

    /** @throws Exception */
    public function get(AbstractUid $trickId): Trick
    {
        foreach ($this->tricks as $trick) {
            if ($trick->snapshot()->uuid->equals($trickId)) {
                return $trick;
            }
        }

        throw new Exception('Trick not found');
    }

    public function assertTrickEdited(): void
    {
        Assert::assertTrue($this->edited);
    }

    public function assertTrickSaved(): void
    {
        Assert::assertTrue($this->saved);
    }

    public function assertTrickDoesNotExist(string $string)
    {
        $uuids = array_map(fn (Trick $trick) => $trick->snapshot()->uuid->toRfc4122(), $this->tricks);
        Assert::assertNotContains($string, $uuids);
    }

    public function delete(AbstractUid $trickId): bool
    {
        foreach ($this->tricks as $key => $trick) {
            if ($trick->snapshot()->uuid->equals($trickId)) {
                unset($this->tricks[$key]);

                return true;
            }
        }

        return false;
    }
}
