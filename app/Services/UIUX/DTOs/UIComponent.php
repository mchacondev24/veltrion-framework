<?php

namespace Veltrion\Services\UIUX\DTOs;

use Veltrion\Services\UIUX\Enums\ComponentCategory;

class UIComponent
{
    /**
     * @param array<string, UIComponentProperty> $properties
     * @param array<int, string> $events
     * @param array<int, UIComponent> $children
     * @param array<string, mixed> $platformMappings
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly ComponentCategory $category,
        public readonly array $properties = [],
        public readonly array $events = [],
        public readonly array $children = [],
        public readonly array $accessibilityRules = [],
        public readonly array $responsiveBehavior = [],
        public readonly array $platformMappings = [],
        public readonly array $dependencies = []
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category->value,
            'properties' => array_map(fn($p) => $p instanceof UIComponentProperty ? $p->toArray() : $p, $this->properties),
            'events' => $this->events,
            'children' => array_map(fn($c) => $c instanceof UIComponent ? $c->toArray() : $c, $this->children),
            'accessibility_rules' => $this->accessibilityRules,
            'responsive_behavior' => $this->responsiveBehavior,
            'platform_mappings' => $this->platformMappings,
            'dependencies' => $this->dependencies,
        ];
    }
}
