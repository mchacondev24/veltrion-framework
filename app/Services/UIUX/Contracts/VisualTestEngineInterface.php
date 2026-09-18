<?php

namespace Veltrion\Services\UIUX\Contracts;

use Veltrion\Services\UIUX\DTOs\ScreenModel;
use Veltrion\Services\UIUX\DTOs\DesignSystem;
use Veltrion\Services\UIUX\DTOs\VisualTestResult;

interface VisualTestEngineInterface
{
    public function runVisualRegressionTest(
        ScreenModel $screen,
        DesignSystem $designSystem,
        string $platform,
        string $theme = 'light'
    ): VisualTestResult;
}
