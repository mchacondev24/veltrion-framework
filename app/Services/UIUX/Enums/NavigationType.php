<?php

namespace Veltrion\Services\UIUX\Enums;

enum NavigationType: string
{
    case SIDEBAR = 'sidebar';
    case TOPBAR = 'topbar';
    case BOTTOM_NAV = 'bottom_nav';
    case STACK = 'stack';
    case DRAWER = 'drawer';
    case TABS = 'tabs';
    case BREADCRUMB = 'breadcrumb';
    case MODAL = 'modal';
}
