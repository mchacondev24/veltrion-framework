<?php

namespace Veltrion\Services\UIUX\DTOs;

class ValidationRule
{
    public function __construct(
        public readonly string $rule, // required, min, max, email, pattern, numeric, date
        public readonly mixed $value = null,
        public readonly ?string $errorMessage = null
    ) {}

    public function toArray(): array
    {
        return [
            'rule' => $this->rule,
            'value' => $this->value,
            'error_message' => $this->errorMessage,
        ];
    }
}
