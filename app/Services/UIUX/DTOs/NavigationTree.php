<?php

namespace Veltrion\Services\UIUX\DTOs;

use Veltrion\Services\UIUX\Enums\NavigationType;

class NavigationTree
{
    /**
     * @param array<int, NavigationNode> $nodes
     */
    public function __construct(
        public readonly NavigationType $type,
        public readonly array $nodes = [],
        public readonly ?string $brandTitle = null,
        public readonly ?string $brandLogo = null
    ) {}

    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'brand_title' => $this->brandTitle,
            'brand_logo' => $this->brandLogo,
            'nodes' => array_map(fn($n) => $n instanceof NavigationNode ? $n->toArray() : $n, $this->nodes),
        ];
    }
}
