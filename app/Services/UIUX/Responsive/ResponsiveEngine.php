<?php

namespace Veltrion\Services\UIUX\Responsive;

use Veltrion\Services\UIUX\Contracts\ResponsiveEngineInterface;
use Veltrion\Services\UIUX\DTOs\ScreenModel;
use Veltrion\Services\UIUX\Enums\BreakpointType;

class ResponsiveEngine implements ResponsiveEngineInterface
{
    public function getBreakpointDefinition(BreakpointType $type): array
    {
        return [
            'type' => $type->value,
            'min_width' => $type->defaultPixelWidth(),
            'columns' => match($type) {
                BreakpointType::XS => 4,
                BreakpointType::SM => 6,
                BreakpointType::MD => 8,
                BreakpointType::LG, BreakpointType::XL, BreakpointType::XXL => 12,
            },
            'min_touch_target_px' => match($type) {
                BreakpointType::XS, BreakpointType::SM => 44, // WCAG / Apple HIG touch target
                default => 32,
            }
        ];
    }

    public function calculateGridSpan(int $desktopSpan, BreakpointType $targetBreakpoint): int
    {
        return match($targetBreakpoint) {
            BreakpointType::XS, BreakpointType::SM => 12, // Full width on small screens
            BreakpointType::MD => min(12, $desktopSpan * 2), // Double width on tablets
            default => $desktopSpan,
        };
    }

    public function optimizeScreenLayout(ScreenModel $screen, BreakpointType $breakpoint): array
    {
        $def = $this->getBreakpointDefinition($breakpoint);
        return [
            'screen_id' => $screen->id,
            'breakpoint' => $breakpoint->value,
            'container_columns' => $def['columns'],
            'touch_target_guarantee_px' => $def['min_touch_target_px'],
            'stack_direction' => in_array($breakpoint, [BreakpointType::XS, BreakpointType::SM]) ? 'vertical' : 'horizontal',
        ];
    }
}
