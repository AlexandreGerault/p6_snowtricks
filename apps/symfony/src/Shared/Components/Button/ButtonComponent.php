<?php

declare(strict_types=1);

namespace App\Shared\Components\Button;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('button')]
class ButtonComponent
{
    private ButtonTypes $type = ButtonTypes::BUTTON;
    private ButtonColorVariants $color = ButtonColorVariants::PRIMARY;

    public string $label;

    public function __construct()
    {
    }

    public function getType(): string
    {
        return $this->type->value;
    }

    public function getColor(): string
    {
        return match($this->color) {
            ButtonColorVariants::PRIMARY => 'bg-blue-500 text-white',
            ButtonColorVariants::SECONDARY => 'border border-blue-500 bg-white',
            ButtonColorVariants::DANGER => 'bg-red-500 text-white',
        };
    }

    public function setType(string $type): void
    {
        $this->type = ButtonTypes::from($type);
    }

    public function setColor(string $color): void
    {
        $this->color = ButtonColorVariants::from($color);
    }
}
