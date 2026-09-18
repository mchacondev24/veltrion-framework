<?php

namespace Veltrion\Services\UIUX\Enums;

enum ComponentCategory: string
{
    case FORM = 'form';
    case DISPLAY = 'display';
    case NAVIGATION = 'navigation';
    case FEEDBACK = 'feedback';
    case LAYOUT = 'layout';
    case DATA_VISUALIZATION = 'data_visualization';
    case OVERLAY = 'overlay';
}
