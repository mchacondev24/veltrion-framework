<?php

namespace Veltrion\Services\UIUX\Contracts;

use Veltrion\Services\UIUX\DTOs\NavigationTree;
use Veltrion\Services\UIUX\Enums\NavigationType;

interface NavigationPlannerInterface
{
    /**
     * @param array<string, \Veltrion\Services\UIUX\DTOs\ScreenModel> $screens
     */
    public function planNavigation(
        array $screens,
        NavigationType $type = NavigationType::SIDEBAR,
        string $platform = 'react'
    ): NavigationTree;
}
