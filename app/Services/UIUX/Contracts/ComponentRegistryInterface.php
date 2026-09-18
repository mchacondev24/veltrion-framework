<?php

namespace Veltrion\Services\UIUX\Contracts;

use Veltrion\Services\UIUX\DTOs\UIComponent;
use Veltrion\Services\UIUX\Enums\ComponentCategory;

interface ComponentRegistryInterface
{
    public function register(UIComponent $component): void;
    public function get(string $name): ?UIComponent;
    public function has(string $name): bool;
    /**
     * @return array<string, UIComponent>
     */
    public function all(): array;
    /**
     * @return array<string, UIComponent>
     */
    public function getByCategory(ComponentCategory $category): array;
}
