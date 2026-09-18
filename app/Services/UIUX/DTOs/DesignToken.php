<?php

namespace Veltrion\Services\UIUX\DTOs;

class DesignToken
{
    public function __construct(
        public readonly string $name,
        public readonly string $category, // color, spacing, typography, radius, shadow, transition, elevation, opacity
        public readonly string|int|float $value,
        public readonly ?string $unit = null, // px, rem, em, ms, %
        public readonly ?string $description = null
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'category' => $this->category,
            'value' => $this->value,
            'unit' => $this->unit,
            'description' => $this->description,
        ];
    }
}
