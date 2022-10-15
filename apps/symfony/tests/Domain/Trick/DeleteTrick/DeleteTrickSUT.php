<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\DeleteTrick;

use App\Tests\Domain\Trick\Adapters\InMemoryTrickGateway;
use App\Trick\Core\Trick;
use App\Trick\Core\UseCases\Commands\DeleteTrick\DeleteTrick;
use App\Trick\Core\UseCases\Commands\DeleteTrick\DeleteTrickInputData;

class DeleteTrickSUT
{
    /** @var Trick[] */
    private array $tricks;

    private InMemoryTrickGateway $repository;
    private string $id;
    private DeleteTrickOutputPort $output;

    public static function new(): self
    {
        return new self();
    }

    /** @param array<Trick> $tricks */
    public function withTricks(array $tricks): static
    {
        $this->tricks = $tricks;

        return $this;
    }

    public function id(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function run(): static
    {
        $this->repository = new InMemoryTrickGateway($this->tricks);
        $this->output = new DeleteTrickOutputPort();

        $deleteTrick = new DeleteTrick($this->repository);

        $input = new DeleteTrickInputData($this->trickId ?? '8c75058e-72ce-43a3-bdc1-65274c3a2f38');
        $deleteTrick->executes($input, $this->output);

        return $this;
    }

    public function repository(): InMemoryTrickGateway
    {
        return $this->repository;
    }

    public function output(): DeleteTrickOutputPort
    {
        return $this->output;
    }
}
