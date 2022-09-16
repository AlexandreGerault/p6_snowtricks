<?php

namespace App\Tests\Domain\Security\AskPasswordReset;

use App\Security\Core\UseCases\AskPasswordReset\AskPasswordReset;
use App\Security\Core\UseCases\AskPasswordReset\AskPasswordResetInputData;
use App\Security\Core\User;
use App\Tests\Domain\Security\Adapters\FakePasswordResetTokenGenerator;
use App\Tests\Domain\Security\Adapters\InMemoryNotifications;
use App\Tests\Domain\Security\Adapters\InMemoryUserRepository;

class AskPasswordResetSUT
{
    private AskPasswordResetOutputPort $outputPort;

    private InMemoryUserRepository $userRepository;

    private AskPasswordReset $askPasswordReset;
    private User $user;
    private string $email;
    private InMemoryNotifications $notifications;

    public static function new(): self
    {
        return new self();
    }

    public function withEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function withUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function run(): static
    {
        $this->outputPort = new AskPasswordResetOutputPort();
        $this->userRepository = new InMemoryUserRepository([$this->user]);
        $this->notifications = new InMemoryNotifications();

        $passwordResetGenerator = new FakePasswordResetTokenGenerator();

        $this->askPasswordReset = new AskPasswordReset($this->userRepository, $passwordResetGenerator, $this->notifications);

        $input = new AskPasswordResetInputData($this->email);
        $this->askPasswordReset->executes($input, $this->outputPort);

        return $this;
    }

    public function outputPort(): AskPasswordResetOutputPort
    {
        return $this->outputPort;
    }

    public function userRepository(): InMemoryUserRepository
    {
        return $this->userRepository;
    }

    public function notifications(): InMemoryNotifications
    {
        return $this->notifications;
    }
}
