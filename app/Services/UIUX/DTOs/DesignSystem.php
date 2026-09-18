<?php

namespace Veltrion\Services\UIUX\DTOs;

class DesignSystem
{
    /**
     * @param array<string, Theme> $themes
     * @param array<string, Breakpoint> $breakpoints
     * @param array<string, DesignToken> $globalTokens
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly BrandingConfig $branding,
        public readonly array $themes = [],
        public readonly array $breakpoints = [],
        public readonly array $globalTokens = []
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'branding' => $this->branding->toArray(),
            'themes' => array_map(fn($t) => $t instanceof Theme ? $t->toArray() : $t, $this->themes),
            'breakpoints' => array_map(fn($b) => $b instanceof Breakpoint ? $b->toArray() : $b, $this->breakpoints),
            'global_tokens' => array_map(fn($tok) => $tok instanceof DesignToken ? $tok->toArray() : $tok, $this->globalTokens),
        ];
    }
}
