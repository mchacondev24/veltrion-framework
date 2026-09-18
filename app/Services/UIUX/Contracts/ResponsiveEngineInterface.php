<?php

namespace Veltrion\Services\UIUX\Contracts;

use Veltrion\Services\UIUX\DTOs\ScreenModel;
use Veltrion\Services\UIUX\Enums\BreakpointType;

interface ResponsiveEngineInterface
{
    public function getBreakpointDefinition(BreakpointType $type): array;
    public function calculateGridSpan(int $desktopSpan, BreakpointType $targetBreakpoint): int;
    public function optimizeScreenLayout(ScreenModel $screen, BreakpointType $breakpoint): array;
}
