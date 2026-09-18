<?php

namespace Veltrion\Services\UIUX\DTOs;

class ScreenModel
{
    /**
     * @param array<int, UIComponent> $components
     * @param array<int, ScreenAction> $actions
     * @param array<string, ScreenState> $states
     * @param array<int, DataSource> $dataSources
     */
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $route,
        public readonly string $layoutType, // dashboard, crud_list, form, detail, blank, modal
        public readonly string $purpose,
        public readonly array $requiredRoles = [],
        public readonly array $components = [],
        public readonly array $actions = [],
        public readonly array $states = [],
        public readonly array $dataSources = [],
        public readonly array $responsiveSettings = [],
        public readonly array $metadata = []
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'route' => $this->route,
            'layout_type' => $this->layoutType,
            'purpose' => $this->purpose,
            'required_roles' => $this->requiredRoles,
            'components' => array_map(fn($c) => $c instanceof UIComponent ? $c->toArray() : $c, $this->components),
            'actions' => array_map(fn($a) => $a instanceof ScreenAction ? $a->toArray() : $a, $this->actions),
            'states' => array_map(fn($s) => $s instanceof ScreenState ? $s->toArray() : $s, $this->states),
            'data_sources' => array_map(fn($d) => $d instanceof DataSource ? $d->toArray() : $d, $this->dataSources),
            'responsive_settings' => $this->responsiveSettings,
            'metadata' => $this->metadata,
        ];
    }
}
