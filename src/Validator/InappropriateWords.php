<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class InappropriateWords extends Constraint
{
    public string $message = 'Ce mot n\'est pas autorisé : "{{ word }}".';

    public array $words = [];

    public function __construct(array $words = [], ?string $message = null, array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);
        $this->words = $words;
        $this->message = $message ?? $this->message;
    }
}
