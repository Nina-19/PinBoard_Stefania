<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class InappropriateWordsValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof InappropriateWords) {
            throw new \InvalidArgumentException('Contrainte inattendue.');
        }

        if (null === $value || '' === $value) {
            return;
        }

        foreach ($constraint->words as $word) {
            if (stripos($value, $word) !== false) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ word }}', $word)
                    ->addViolation();
            }
        }
    }
}
