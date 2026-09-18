<?php

namespace Veltrion\Services\UIUX\DTOs;

class Theme
{
    /**
     * @param array<string, DesignToken> $tokens
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly bool $isDark = false,
        public readonly array $tokens = [],
        public readonly array $colors = [],
        public readonly array $typography = []
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_dark' => $this->isDark,
            'tokens' => array_map(fn($t) => $t instanceof DesignToken ? $t->toArray() : $t, $this->tokens),
            'colors' => $this->colors,
            'typography' => $this->typography,
        ];
    }
}
