<?php

namespace Veltrion\Services\UIUX\Contracts;

use Veltrion\Services\UIUX\DTOs\ScreenModel;
use Veltrion\Services\UIUX\DTOs\UserFlow;

interface ScreenPlannerInterface
{
    /**
     * @param array<int, UserFlow> $flows
     * @return array<string, ScreenModel>
     */
    public function planScreensFromFlows(array $flows, array $entities = []): array;
    public function planCrudScreens(string $entityName, array $fields): array;
    public function planDashboardScreen(string $dashboardName, array $kpis, array $charts): ScreenModel;
}
