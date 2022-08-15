<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\Adapters;

use App\Trick\Core\Trick;
use App\Trick\Core\TrickGateway;
use Exception;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\AbstractUid;

class InMemoryTrickGateway implements TrickGateway
{
    private bool $edited = false;

    /** @param Trick[] $tricks */
    public function __construct(private array $tricks = [])
    {
    }

    public function save(Trick $trick): void
    {
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

    public function assertTrickEdited()
    {
        Assert::assertTrue($this->edited);
    }
}
