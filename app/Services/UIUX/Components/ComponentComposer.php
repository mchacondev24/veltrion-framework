<?php

namespace Veltrion\Services\UIUX\Components;

use Veltrion\Services\UIUX\DTOs\UIComponent;
use Veltrion\Services\UIUX\DTOs\UIComponentProperty;
use Veltrion\Services\UIUX\Enums\ComponentCategory;

class ComponentComposer
{
    private ComponentRegistry $registry;

    public function __construct(?ComponentRegistry $registry = null)
    {
        $this->registry = $registry ?? new ComponentRegistry();
    }

    /**
     * Composes a search and filter toolbar
     */
    public function composeSearchFilterBar(string $id = 'search_filter_bar'): UIComponent
    {
        return new UIComponent(
            id: $id,
            name: 'SearchFilterBar',
            category: ComponentCategory::LAYOUT,
            properties: [
                'placeholder' => new UIComponentProperty('placeholder', 'string', 'Search records...'),
                'showFilters' => new UIComponentProperty('showFilters', 'boolean', true),
            ],
            events: ['onSearch', 'onFilterChange', 'onReset']
        );
    }

    /**
     * Composes a standard Page Header with title, breadcrumb and action buttons
     */
    public function composePageHeader(string $title, ?string $subtitle = null, array $actions = []): UIComponent
    {
        return new UIComponent(
            id: 'page_header_' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $title)),
            name: 'PageHeader',
            category: ComponentCategory::LAYOUT,
            properties: [
                'title' => new UIComponentProperty('title', 'string', $title, true),
                'subtitle' => new UIComponentProperty('subtitle', 'string', $subtitle),
                'actions' => new UIComponentProperty('actions', 'array', $actions),
            ]
        );
    }
}
