<?php

declare(strict_types=1);

namespace App\Trick\Core\UseCases\Commands\DeleteTrick;

use App\Trick\Core\TrickGateway;
use Symfony\Component\Uid\Uuid;

class DeleteTrick
{
    public function __construct(private readonly TrickGateway $trickGateway)
    {
    }

    public function executes(DeleteTrickInputData $input, DeleteTrickPresenter $output): void
    {
        if (!$this->trickGateway->delete(Uuid::fromString($input->trickId))) {
            $output->trickNotFound();

            return;
        }

        $output->trickDeleted();
    }
}
