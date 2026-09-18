<?php

namespace Veltrion\Services\UIUX\Enums;

enum BreakpointType: string
{
    case XS = 'xs';   // < 576px (Phones portrait)
    case SM = 'sm';   // >= 576px (Phones landscape)
    case MD = 'md';   // >= 768px (Tablets)
    case LG = 'lg';   // >= 992px (Desktops)
    case XL = 'xl';   // >= 1200px (Large Desktops)
    case XXL = 'xxl'; // >= 1400px (Ultra-wide screens)

    public function defaultPixelWidth(): int
    {
        return match($this) {
            self::XS => 360,
            self::SM => 576,
            self::MD => 768,
            self::LG => 992,
            self::XL => 1200,
            self::XXL => 1400,
        };
    }
}
