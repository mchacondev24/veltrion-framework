<?php

namespace Veltrion\Services\UIUX\MasterDetail;

use Veltrion\Services\UIUX\Components\ComponentComposer;
use Veltrion\Services\UIUX\DTOs\DataSource;
use Veltrion\Services\UIUX\DTOs\ScreenAction;
use Veltrion\Services\UIUX\DTOs\ScreenModel;
use Veltrion\Services\UIUX\DTOs\UIComponent;
use Veltrion\Services\UIUX\DTOs\UIComponentProperty;
use Veltrion\Services\UIUX\Enums\ComponentCategory;

class MasterDetailUIGenerator
{
    private ComponentComposer $composer;

    public function __construct()
    {
        $this->composer = new ComponentComposer();
    }

    public function generateMasterDetail(
        string $parentEntity,
        string $childEntity,
        array $parentFields,
        array $childFields
    ): ScreenModel {
        $kebabParent = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $parentEntity));
        $kebabChild = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $childEntity));

        $parentCard = new UIComponent(
            id: "{$kebabParent}_summary_card",
            name: 'Card',
            category: ComponentCategory::LAYOUT,
            properties: [
                'title' => new UIComponentProperty('title', 'string', "{$parentEntity} Information"),
                'fields' => new UIComponentProperty('fields', 'array', $parentFields),
            ]
        );

        $childrenGrid = new UIComponent(
            id: "{$kebabChild}_items_grid",
            name: 'DataGrid',
            category: ComponentCategory::DISPLAY,
            properties: [
                'columns' => new UIComponentProperty('columns', 'array', $childFields),
                'searchable' => new UIComponentProperty('searchable', 'boolean', false),
                'pagination' => new UIComponentProperty('pagination', 'boolean', true),
            ]
        );

        return new ScreenModel(
            id: "{$kebabParent}_with_{$kebabChild}",
            title: "{$parentEntity} with {$childEntity} Details",
            route: "/{$kebabParent}s/:id/{$kebabChild}s",
            layoutType: 'master_detail',
            purpose: "Manage {$parentEntity} and nested {$childEntity} records",
            requiredRoles: ['admin', 'manager'],
            components: [
                $this->composer->composePageHeader("{$parentEntity} & {$childEntity} Breakdown"),
                $parentCard,
                $childrenGrid,
            ],
            actions: [
                new ScreenAction('add_child', "Add {$childEntity}", 'modal', "#modal-add-{$kebabChild}", isPrimary: true),
                new ScreenAction('back', 'Back to List', 'navigation', "/{$kebabParent}s"),
            ],
            dataSources: [
                new DataSource('ds_master', 'api_rest', "/api/{$kebabParent}s/:id", 'GET'),
                new DataSource('ds_details', 'api_rest', "/api/{$kebabParent}s/:id/{$kebabChild}s", 'GET'),
            ]
        );
    }
}
