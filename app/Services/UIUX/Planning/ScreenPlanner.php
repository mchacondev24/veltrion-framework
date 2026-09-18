<?php

namespace Veltrion\Services\UIUX\Planning;

use Veltrion\Services\UIUX\Contracts\ScreenPlannerInterface;
use Veltrion\Services\UIUX\CRUD\CrudUIGenerator;
use Veltrion\Services\UIUX\Dashboard\DashboardGenerator;
use Veltrion\Services\UIUX\DTOs\ScreenModel;

class ScreenPlanner implements ScreenPlannerInterface
{
    private CrudUIGenerator $crudGenerator;
    private DashboardGenerator $dashboardGenerator;

    public function __construct()
    {
        $this->crudGenerator = new CrudUIGenerator();
        $this->dashboardGenerator = new DashboardGenerator();
    }

    public function planScreensFromFlows(array $flows, array $entities = []): array
    {
        $screens = [];

        // Always include Dashboard
        $dashboard = $this->dashboardGenerator->generateDashboard();
        $screens['dashboard'] = $dashboard;

        // Plan CRUD screens for each entity
        foreach ($entities as $name => $fields) {
            $crudScreens = $this->planCrudScreens($name, $fields);
            foreach ($crudScreens as $id => $screen) {
                $screens[$id] = $screen;
            }
        }

        return $screens;
    }

    public function planCrudScreens(string $entityName, array $fields): array
    {
        return $this->crudGenerator->generateCrudScreens($entityName, $fields);
    }

    public function planDashboardScreen(string $dashboardName, array $kpis, array $charts): ScreenModel
    {
        return $this->dashboardGenerator->generateDashboard(
            id: 'dashboard',
            title: $dashboardName,
            kpis: $kpis,
            charts: $charts
        );
    }
}
