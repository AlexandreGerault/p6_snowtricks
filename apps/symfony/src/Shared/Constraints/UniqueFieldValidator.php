<?php

declare(strict_types=1);

namespace App\Shared\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueFieldValidator extends ConstraintValidator
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueField) {
            throw new UnexpectedTypeException($constraint, UniqueField::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->alreadyExists($value, $constraint->field, $constraint->table)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter("{{ value }}", $value)
                ->setParameter("{{ field }}", $constraint->fieldName)
                ->addViolation();
        }
    }

    private function alreadyExists(string $value, string $field, string $table): bool
    {
        $connection = $this->entityManager->getConnection();
        $query = "SELECT {$field} FROM {$table} WHERE {$field} = :value;";
        $statement = $connection->prepare($query);
        $resultSet = $statement->executeQuery(['value' => $value]);

        return count($resultSet->fetchAllAssociative()) > 0;
    }
}
