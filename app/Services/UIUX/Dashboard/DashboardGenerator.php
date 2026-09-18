<?php

namespace Veltrion\Services\UIUX\Dashboard;

use Veltrion\Services\UIUX\Components\ComponentComposer;
use Veltrion\Services\UIUX\DTOs\DataSource;
use Veltrion\Services\UIUX\DTOs\ScreenAction;
use Veltrion\Services\UIUX\DTOs\ScreenModel;
use Veltrion\Services\UIUX\DTOs\ScreenState;
use Veltrion\Services\UIUX\DTOs\UIComponent;
use Veltrion\Services\UIUX\DTOs\UIComponentProperty;
use Veltrion\Services\UIUX\Enums\ComponentCategory;
use Veltrion\Services\UIUX\Enums\UIStateType;

class DashboardGenerator
{
    private ComponentComposer $composer;

    public function __construct()
    {
        $this->composer = new ComponentComposer();
    }

    /**
     * @param array<int, array> $kpis [['title' => 'Revenue', 'value' => '$45,000', 'trend' => '+12%']]
     * @param array<int, array> $charts [['title' => 'Monthly Sales', 'type' => 'bar']]
     */
    public function generateDashboard(
        string $id = 'main_dashboard',
        string $title = 'Executive Dashboard',
        array $kpis = [],
        array $charts = []
    ): ScreenModel {
        $components = [];

        // Header
        $components[] = $this->composer->composePageHeader($title, "Real-time metrics, analytics and operations");

        // KPI Section
        $kpiComponents = [];
        if (empty($kpis)) {
            $kpis = [
                ['title' => 'Total Users', 'value' => '1,420', 'trend' => '+8.4%', 'isPositive' => true],
                ['title' => 'Active Sessions', 'value' => '312', 'trend' => '+14.2%', 'isPositive' => true],
                ['title' => 'Conversion Rate', 'value' => '3.8%', 'trend' => '-0.5%', 'isPositive' => false],
                ['title' => 'Total Revenue', 'value' => '$94,250', 'trend' => '+22.1%', 'isPositive' => true],
            ];
        }

        foreach ($kpis as $idx => $kpi) {
            $kpiComponents[] = new UIComponent(
                id: "kpi_{$idx}",
                name: 'KpiCard',
                category: ComponentCategory::DATA_VISUALIZATION,
                properties: [
                    'title' => new UIComponentProperty('title', 'string', $kpi['title'], true),
                    'value' => new UIComponentProperty('value', 'string', $kpi['value'], true),
                    'trend' => new UIComponentProperty('trend', 'string', $kpi['trend'] ?? null),
                    'isPositive' => new UIComponentProperty('isPositive', 'boolean', $kpi['isPositive'] ?? true),
                ]
            );
        }

        $components[] = new UIComponent(
            id: 'kpi_grid',
            name: 'KpiGrid',
            category: ComponentCategory::LAYOUT,
            children: $kpiComponents
        );

        // Charts
        if (empty($charts)) {
            $charts = [
                ['title' => 'Performance History', 'type' => 'area'],
                ['title' => 'Category Breakdown', 'type' => 'donut'],
            ];
        }

        $chartComponents = [];
        foreach ($charts as $idx => $chart) {
            $chartComponents[] = new UIComponent(
                id: "chart_{$idx}",
                name: 'Chart',
                category: ComponentCategory::DATA_VISUALIZATION,
                properties: [
                    'title' => new UIComponentProperty('title', 'string', $chart['title']),
                    'type' => new UIComponentProperty('type', 'string', $chart['type'] ?? 'bar'),
                    'data' => new UIComponentProperty('data', 'array', []),
                    'height' => new UIComponentProperty('height', 'number', 320),
                ]
            );
        }

        $components[] = new UIComponent(
            id: 'charts_grid',
            name: 'ChartsGrid',
            category: ComponentCategory::LAYOUT,
            children: $chartComponents
        );

        return new ScreenModel(
            id: $id,
            title: $title,
            route: '/dashboard',
            layoutType: 'dashboard',
            purpose: 'Executive overview of system metrics and status',
            requiredRoles: ['admin', 'manager'],
            components: $components,
            actions: [
                new ScreenAction('refresh', 'Refresh Data', 'api_call', '/api/dashboard/metrics'),
                new ScreenAction('export', 'Export PDF', 'export', '/api/dashboard/export'),
            ],
            states: [
                UIStateType::LOADING->value => new ScreenState(UIStateType::LOADING, "Loading dashboard metrics..."),
                UIStateType::ERROR->value => new ScreenState(UIStateType::ERROR, "Could not fetch metrics"),
            ],
            dataSources: [
                new DataSource('ds_dashboard', 'api_rest', '/api/dashboard/metrics', 'GET')
            ]
        );
    }
}
