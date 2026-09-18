<?php

namespace Veltrion\Services\UIUX\DTOs;

use Veltrion\Services\UIUX\Enums\NavigationType;

class NavigationNode
{
    /**
     * @param array<int, NavigationNode> $children
     */
    public function __construct(
        public readonly string $id,
        public readonly string $label,
        public readonly ?string $icon,
        public readonly ?string $route,
        public readonly ?string $screenId = null,
        public readonly array $requiredRoles = [],
        public readonly array $children = [],
        public readonly int $order = 0
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'icon' => $this->icon,
            'route' => $this->route,
            'screen_id' => $this->screenId,
            'required_roles' => $this->requiredRoles,
            'children' => array_map(fn($c) => $c instanceof NavigationNode ? $c->toArray() : $c, $this->children),
            'order' => $this->order,
        ];
    }
}
