<?php

namespace App\Trick\UserInterface\Form\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class NullableImageWithDefaultPath extends Constraint
{
    public string $message = "The field '{{ nullableField }}' is required or provide a {{ fallbackField }}.";

    public function getTargets(): array|string
    {
        return self::CLASS_CONSTRAINT;
    }
}