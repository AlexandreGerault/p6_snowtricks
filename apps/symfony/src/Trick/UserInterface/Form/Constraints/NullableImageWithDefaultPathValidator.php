<?php

namespace App\Trick\UserInterface\Form\Constraints;

use App\Trick\UserInterface\Form\Type\ImageDTO;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NullableImageWithDefaultPathValidator extends ConstraintValidator
{
    /**
     * @param ImageDTO $value
     * @param NullableImageWithDefaultPath $constraint
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if ($value->image === null && $value->path === null) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}