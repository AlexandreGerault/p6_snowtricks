<?php

declare(strict_types=1);

namespace App\Shared\Components\Button;

enum ButtonColorVariants: string
{
    case PRIMARY = 'primary';
    case SECONDARY = 'secondary';
    case DANGER = 'danger';
}
