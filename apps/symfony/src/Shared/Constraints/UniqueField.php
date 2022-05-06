<?php

declare(strict_types=1);

namespace App\Shared\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class UniqueField extends Constraint
{
    public string $message = '{{ value }} est déjà utilisé comme {{ field }}.';

    public string $field;

    public string $table;

    public string $fieldName;
}
