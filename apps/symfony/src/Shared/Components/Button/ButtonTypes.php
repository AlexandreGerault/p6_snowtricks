<?php

declare(strict_types=1);

namespace App\Shared\Components\Button;

enum ButtonTypes: string
{
    case SUBMIT = 'submit';
    case BUTTON = 'button';
    case RESET = 'reset';
}
