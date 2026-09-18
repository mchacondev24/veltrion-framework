<?php

namespace Veltrion\Services\UIUX\Contracts;

use Veltrion\Services\UIUX\DTOs\ScreenModel;
use Veltrion\Services\UIUX\DTOs\NavigationTree;
use Veltrion\Services\UIUX\DTOs\UserFlow;

interface UIValidatorInterface
{
    /**
     * @param array<string, ScreenModel> $screens
     * @param array<string, UserFlow> $flows
     */
    public function validateArchitecture(array $screens, NavigationTree $navigation, array $flows = []): array;
}
