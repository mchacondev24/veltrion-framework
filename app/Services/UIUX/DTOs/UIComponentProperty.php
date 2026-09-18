<?php

namespace Veltrion\Services\UIUX\DTOs;

class UIComponentProperty
{
    public function __construct(
        public readonly string $name,
        public readonly string $type, // string, number, boolean, array, object, callback
        public readonly mixed $defaultValue = null,
        public readonly bool $isRequired = false,
        public readonly ?string $description = null
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'default_value' => $this->defaultValue,
            'is_required' => $this->isRequired,
            'description' => $this->description,
        ];
    }
}
