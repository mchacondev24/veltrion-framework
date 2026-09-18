<?php

namespace Veltrion\Services\UIUX\CRUD;

use Veltrion\Services\UIUX\Components\ComponentComposer;
use Veltrion\Services\UIUX\Components\ComponentRegistry;
use Veltrion\Services\UIUX\DTOs\DataSource;
use Veltrion\Services\UIUX\DTOs\ScreenAction;
use Veltrion\Services\UIUX\DTOs\ScreenModel;
use Veltrion\Services\UIUX\DTOs\ScreenState;
use Veltrion\Services\UIUX\DTOs\UIComponent;
use Veltrion\Services\UIUX\DTOs\UIComponentProperty;
use Veltrion\Services\UIUX\Enums\ComponentCategory;
use Veltrion\Services\UIUX\Enums\UIStateType;
use Veltrion\Services\UIUX\Forms\FormGenerator;

class CrudUIGenerator
{
    private FormGenerator $formGenerator;
    private ComponentComposer $composer;

    public function __construct()
    {
        $this->formGenerator = new FormGenerator();
        $this->composer = new ComponentComposer();
    }

    /**
     * Generates standard CRUD screens (List, Create, Edit, Detail) for any domain entity
     * @return array<string, ScreenModel>
     */
    public function generateCrudScreens(string $entityName, array $fields): array
    {
        $pluralName = $entityName . 's';
        $kebabEntity = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $entityName));
        $kebabPlural = $kebabEntity . 's';

        $formFields = $this->formGenerator->generateFields($fields);
        $screens = [];

        // 1. List Screen
        $listTable = new UIComponent(
            id: "{$kebabEntity}_datagrid",
            name: 'DataGrid',
            category: ComponentCategory::DISPLAY,
            properties: [
                'columns' => new UIComponentProperty('columns', 'array', array_keys($fields)),
                'searchable' => new UIComponentProperty('searchable', 'boolean', true),
                'pagination' => new UIComponentProperty('pagination', 'boolean', true),
            ]
        );

        $screens["{$kebabPlural}.index"] = new ScreenModel(
            id: "{$kebabPlural}.index",
            title: "{$pluralName} Management",
            route: "/{$kebabPlural}",
            layoutType: 'crud_list',
            purpose: "Browse, filter and manage {$pluralName}",
            requiredRoles: ['admin', 'manager', 'user'],
            components: [
                $this->composer->composePageHeader("{$pluralName} Overview", "Manage your company {$kebabPlural}"),
                $this->composer->composeSearchFilterBar(),
                $listTable,
            ],
            actions: [
                new ScreenAction('create', "New {$entityName}", 'navigation', "/{$kebabPlural}/create", isPrimary: true),
                new ScreenAction('refresh', "Refresh", 'api_call', "/api/{$kebabPlural}"),
                new ScreenAction('export', "Export CSV", 'export', "/api/{$kebabPlural}/export"),
            ],
            states: [
                UIStateType::INITIAL->value => new ScreenState(UIStateType::INITIAL, "Ready"),
                UIStateType::LOADING->value => new ScreenState(UIStateType::LOADING, "Loading {$pluralName}..."),
                UIStateType::EMPTY->value => new ScreenState(UIStateType::EMPTY, "No {$pluralName} found", "Create your first record to get started", "New {$entityName}", "/{$kebabPlural}/create"),
                UIStateType::ERROR->value => new ScreenState(UIStateType::ERROR, "Failed to load {$pluralName}"),
            ],
            dataSources: [
                new DataSource('ds_list', 'api_rest', "/api/{$kebabPlural}", 'GET', entityClass: $entityName)
            ]
        );

        // 2. Create Screen
        $createForm = new UIComponent(
            id: "{$kebabEntity}_create_form",
            name: 'Form',
            category: ComponentCategory::FORM,
            properties: [
                'fields' => new UIComponentProperty('fields', 'array', array_map(fn($f) => $f->toArray(), $formFields)),
                'submitLabel' => new UIComponentProperty('submitLabel', 'string', "Save {$entityName}"),
            ],
            events: ['onSubmit', 'onCancel']
        );

        $screens["{$kebabPlural}.create"] = new ScreenModel(
            id: "{$kebabPlural}.create",
            title: "Create {$entityName}",
            route: "/{$kebabPlural}/create",
            layoutType: 'form',
            purpose: "Create a new {$entityName} record",
            requiredRoles: ['admin', 'manager'],
            components: [
                $this->composer->composePageHeader("Create {$entityName}"),
                $createForm,
            ],
            actions: [
                new ScreenAction('save', "Save {$entityName}", 'submit', "/api/{$kebabPlural}", isPrimary: true),
                new ScreenAction('cancel', "Cancel", 'navigation', "/{$kebabPlural}"),
            ],
            states: [
                UIStateType::SAVING->value => new ScreenState(UIStateType::SAVING, "Saving record..."),
                UIStateType::VALIDATION_ERROR->value => new ScreenState(UIStateType::VALIDATION_ERROR, "Please review highlighted errors"),
            ],
            dataSources: [
                new DataSource('ds_create', 'api_rest', "/api/{$kebabPlural}", 'POST', entityClass: $entityName)
            ]
        );

        // 3. Edit Screen
        $editForm = new UIComponent(
            id: "{$kebabEntity}_edit_form",
            name: 'Form',
            category: ComponentCategory::FORM,
            properties: [
                'fields' => new UIComponentProperty('fields', 'array', array_map(fn($f) => $f->toArray(), $formFields)),
                'submitLabel' => new UIComponentProperty('submitLabel', 'string', "Update {$entityName}"),
            ],
            events: ['onSubmit', 'onCancel']
        );

        $screens["{$kebabPlural}.edit"] = new ScreenModel(
            id: "{$kebabPlural}.edit",
            title: "Edit {$entityName}",
            route: "/{$kebabPlural}/:id/edit",
            layoutType: 'form',
            purpose: "Edit existing {$entityName} details",
            requiredRoles: ['admin', 'manager'],
            components: [
                $this->composer->composePageHeader("Edit {$entityName}"),
                $editForm,
            ],
            actions: [
                new ScreenAction('update', "Update {$entityName}", 'submit', "/api/{$kebabPlural}/:id", isPrimary: true),
                new ScreenAction('delete', "Delete", 'delete', "/api/{$kebabPlural}/:id", requiresConfirmation: true, confirmationMessage: "Are you sure you want to delete this {$entityName}?"),
                new ScreenAction('cancel', "Cancel", 'navigation', "/{$kebabPlural}"),
            ],
            dataSources: [
                new DataSource('ds_get', 'api_rest', "/api/{$kebabPlural}/:id", 'GET', entityClass: $entityName),
                new DataSource('ds_update', 'api_rest', "/api/{$kebabPlural}/:id", 'PUT', entityClass: $entityName)
            ]
        );

        // 4. Detail Screen
        $detailCard = new UIComponent(
            id: "{$kebabEntity}_detail_card",
            name: 'Card',
            category: ComponentCategory::LAYOUT,
            properties: [
                'title' => new UIComponentProperty('title', 'string', "{$entityName} Information"),
                'fields' => new UIComponentProperty('fields', 'array', array_keys($fields)),
            ]
        );

        $screens["{$kebabPlural}.show"] = new ScreenModel(
            id: "{$kebabPlural}.show",
            title: "{$entityName} Details",
            route: "/{$kebabPlural}/:id",
            layoutType: 'detail',
            purpose: "View single {$entityName} attributes and audit trail",
            requiredRoles: ['admin', 'manager', 'user'],
            components: [
                $this->composer->composePageHeader("{$entityName} Overview"),
                $detailCard,
            ],
            actions: [
                new ScreenAction('edit', "Edit {$entityName}", 'navigation', "/{$kebabPlural}/:id/edit", isPrimary: true),
                new ScreenAction('back', "Back to List", 'navigation', "/{$kebabPlural}"),
            ],
            dataSources: [
                new DataSource('ds_show', 'api_rest', "/api/{$kebabPlural}/:id", 'GET', entityClass: $entityName)
            ]
        );

        return $screens;
    }
}
