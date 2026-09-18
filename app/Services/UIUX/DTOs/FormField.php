<?php

namespace Veltrion\Services\UIUX\DTOs;

class FormField
{
    /**
     * @param array<int, ValidationRule> $rules
     * @param array<string, string> $options (for select/radio)
     */
    public function __construct(
        public readonly string $name,
        public readonly string $label,
        public readonly string $type, // text, number, decimal, email, password, select, date, datetime, textarea, switch, checkbox, file
        public readonly ?string $placeholder = null,
        public readonly mixed $defaultValue = null,
        public readonly array $rules = [],
        public readonly array $options = [],
        public readonly bool $isRequired = false,
        public readonly ?string $helpText = null,
        public readonly array $dependencies = [],
        public readonly int $gridColumns = 12 // 1 to 12 in responsive grid
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'label' => $this->label,
            'type' => $this->type,
            'placeholder' => $this->placeholder,
            'default_value' => $this->defaultValue,
            'rules' => array_map(fn($r) => $r instanceof ValidationRule ? $r->toArray() : $r, $this->rules),
            'options' => $this->options,
            'is_required' => $this->isRequired,
            'help_text' => $this->helpText,
            'dependencies' => $this->dependencies,
            'grid_columns' => $this->gridColumns,
        ];
    }
}
