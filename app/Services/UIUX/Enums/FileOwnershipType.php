<?php

namespace Veltrion\Services\UIUX\Enums;

enum FileOwnershipType: string
{
    case GENERATED = 'GENERATED';
    case GENERATED_MODIFIED = 'GENERATED_MODIFIED';
    case CUSTOM = 'CUSTOM';
    case PROTECTED = 'PROTECTED';
}
