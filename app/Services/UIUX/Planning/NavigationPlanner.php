<?php

namespace Veltrion\Services\UIUX\Planning;

use Veltrion\Services\UIUX\Contracts\NavigationPlannerInterface;
use Veltrion\Services\UIUX\DTOs\NavigationNode;
use Veltrion\Services\UIUX\DTOs\NavigationTree;
use Veltrion\Services\UIUX\Enums\NavigationType;

class NavigationPlanner implements NavigationPlannerInterface
{
    public function planNavigation(
        array $screens,
        NavigationType $type = NavigationType::SIDEBAR,
        string $platform = 'react'
    ): NavigationTree {
        $nodes = [];
        $order = 0;

        // Group screens by primary concept
        $grouped = [];
        foreach ($screens as $id => $screen) {
            $parts = explode('.', $id);
            $group = count($parts) > 1 ? $parts[0] : 'general';
            $grouped[$group][] = $screen;
        }

        // 1. Dashboard entry
        if (isset($screens['dashboard'])) {
            $nodes[] = new NavigationNode(
                id: 'nav_dashboard',
                label: 'Dashboard',
                icon: 'dashboard',
                route: '/dashboard',
                screenId: 'dashboard',
                order: ++$order
            );
        }

        // 2. Add other screens/groups
        foreach ($grouped as $group => $screenList) {
            if ($group === 'general' || $group === 'dashboard') {
                continue;
            }

            $primaryScreen = $screenList[0];
            $nodes[] = new NavigationNode(
                id: "nav_{$group}",
                label: ucwords(str_replace(['-', '_'], ' ', $group)),
                icon: 'folder',
                route: $primaryScreen->route,
                screenId: $primaryScreen->id,
                order: ++$order
            );
        }

        return new NavigationTree(
            type: $type,
            nodes: $nodes,
            brandTitle: 'Veltrion Enterprise',
            brandLogo: '/assets/logo.svg'
        );
    }
}
