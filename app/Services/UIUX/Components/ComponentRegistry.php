<?php

namespace Veltrion\Services\UIUX\Components;

use Veltrion\Services\UIUX\Contracts\ComponentRegistryInterface;
use Veltrion\Services\UIUX\DTOs\UIComponent;
use Veltrion\Services\UIUX\DTOs\UIComponentProperty;
use Veltrion\Services\UIUX\Enums\ComponentCategory;

class ComponentRegistry implements ComponentRegistryInterface
{
    /**
     * @var array<string, UIComponent>
     */
    private array $components = [];

    public function __construct()
    {
        $this->registerDefaultComponents();
    }

    public function register(UIComponent $component): void
    {
        $this->components[$component->name] = $component;
    }

    public function get(string $name): ?UIComponent
    {
        return $this->components[$name] ?? null;
    }

    public function has(string $name): bool
    {
        return isset($this->components[$name]);
    }

    public function all(): array
    {
        return $this->components;
    }

    public function getByCategory(ComponentCategory $category): array
    {
        return array_filter($this->components, fn(UIComponent $c) => $c->category === $category);
    }

    private function registerDefaultComponents(): void
    {
        // 1. Button
        $this->register(new UIComponent(
            id: 'cmp_button',
            name: 'Button',
            category: ComponentCategory::FORM,
            properties: [
                'label' => new UIComponentProperty('label', 'string', 'Click me', true),
                'variant' => new UIComponentProperty('variant', 'string', 'primary'),
                'size' => new UIComponentProperty('size', 'string', 'md'),
                'disabled' => new UIComponentProperty('disabled', 'boolean', false),
                'icon' => new UIComponentProperty('icon', 'string', null),
            ],
            events: ['onClick'],
            accessibilityRules: ['WCAG-2.4.7-focus', 'WCAG-4.1.2-name']
        ));

        // 2. Input
        $this->register(new UIComponent(
            id: 'cmp_input',
            name: 'Input',
            category: ComponentCategory::FORM,
            properties: [
                'name' => new UIComponentProperty('name', 'string', '', true),
                'label' => new UIComponentProperty('label', 'string', '', true),
                'type' => new UIComponentProperty('type', 'string', 'text'),
                'placeholder' => new UIComponentProperty('placeholder', 'string', ''),
                'required' => new UIComponentProperty('required', 'boolean', false),
                'error' => new UIComponentProperty('error', 'string', null),
            ],
            events: ['onChange', 'onBlur'],
            accessibilityRules: ['WCAG-1.3.1-info', 'WCAG-3.3.2-labels']
        ));

        // 3. Card
        $this->register(new UIComponent(
            id: 'cmp_card',
            name: 'Card',
            category: ComponentCategory::LAYOUT,
            properties: [
                'title' => new UIComponentProperty('title', 'string', null),
                'subtitle' => new UIComponentProperty('subtitle', 'string', null),
                'padding' => new UIComponentProperty('padding', 'string', 'md'),
            ]
        ));

        // 4. DataGrid / Table
        $this->register(new UIComponent(
            id: 'cmp_datagrid',
            name: 'DataGrid',
            category: ComponentCategory::DISPLAY,
            properties: [
                'columns' => new UIComponentProperty('columns', 'array', [], true),
                'data' => new UIComponentProperty('data', 'array', [], true),
                'pagination' => new UIComponentProperty('pagination', 'boolean', true),
                'pageSize' => new UIComponentProperty('pageSize', 'number', 10),
                'searchable' => new UIComponentProperty('searchable', 'boolean', true),
            ],
            events: ['onRowClick', 'onSort', 'onPageChange'],
            accessibilityRules: ['WCAG-1.3.1-table-headers']
        ));

        // 5. Modal
        $this->register(new UIComponent(
            id: 'cmp_modal',
            name: 'Modal',
            category: ComponentCategory::OVERLAY,
            properties: [
                'isOpen' => new UIComponentProperty('isOpen', 'boolean', false, true),
                'title' => new UIComponentProperty('title', 'string', '', true),
                'size' => new UIComponentProperty('size', 'string', 'md'),
            ],
            events: ['onClose'],
            accessibilityRules: ['WCAG-2.1.2-keyboard-trap', 'WCAG-2.4.3-focus-order']
        ));

        // 6. Alert
        $this->register(new UIComponent(
            id: 'cmp_alert',
            name: 'Alert',
            category: ComponentCategory::FEEDBACK,
            properties: [
                'type' => new UIComponentProperty('type', 'string', 'info'),
                'title' => new UIComponentProperty('title', 'string', null),
                'message' => new UIComponentProperty('message', 'string', '', true),
                'dismissible' => new UIComponentProperty('dismissible', 'boolean', true),
            ]
        ));

        // 7. EmptyState
        $this->register(new UIComponent(
            id: 'cmp_empty_state',
            name: 'EmptyState',
            category: ComponentCategory::FEEDBACK,
            properties: [
                'title' => new UIComponentProperty('title', 'string', 'No data found'),
                'description' => new UIComponentProperty('description', 'string', 'Try adjusting your filters or adding a new record.'),
                'actionLabel' => new UIComponentProperty('actionLabel', 'string', null),
            ],
            events: ['onAction']
        ));

        // 8. Loader / Spinner
        $this->register(new UIComponent(
            id: 'cmp_loader',
            name: 'Loader',
            category: ComponentCategory::FEEDBACK,
            properties: [
                'size' => new UIComponentProperty('size', 'string', 'md'),
                'message' => new UIComponentProperty('message', 'string', 'Loading...'),
            ]
        ));

        // 9. KPI Card
        $this->register(new UIComponent(
            id: 'cmp_kpi',
            name: 'KpiCard',
            category: ComponentCategory::DATA_VISUALIZATION,
            properties: [
                'title' => new UIComponentProperty('title', 'string', '', true),
                'value' => new UIComponentProperty('value', 'string', '0', true),
                'trend' => new UIComponentProperty('trend', 'string', null),
                'isPositive' => new UIComponentProperty('isPositive', 'boolean', true),
                'icon' => new UIComponentProperty('icon', 'string', null),
            ]
        ));

        // 10. Chart
        $this->register(new UIComponent(
            id: 'cmp_chart',
            name: 'Chart',
            category: ComponentCategory::DATA_VISUALIZATION,
            properties: [
                'type' => new UIComponentProperty('type', 'string', 'bar', true),
                'title' => new UIComponentProperty('title', 'string', null),
                'data' => new UIComponentProperty('data', 'array', [], true),
                'height' => new UIComponentProperty('height', 'number', 300),
            ]
        ));
    }
}
