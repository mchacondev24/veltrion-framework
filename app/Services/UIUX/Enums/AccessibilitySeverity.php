<?php

namespace Veltrion\Services\UIUX\Enums;

enum AccessibilitySeverity: string
{
    case CRITICAL = 'CRITICAL';
    case HIGH = 'HIGH';
    case MEDIUM = 'MEDIUM';
    case LOW = 'LOW';
    case INFO = 'INFO';
}
