<?php

namespace Veltrion\Services\UIUX\DTOs;

use Veltrion\Services\UIUX\Enums\BreakpointType;

class Breakpoint
{
    public function __construct(
        public readonly BreakpointType $type,
        public readonly int $minWidth,
        public readonly int $columns = 12,
        public readonly int $gutter = 16,
        public readonly int $margin = 16
    ) {}

    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'min_width' => $this->minWidth,
            'columns' => $this->columns,
            'gutter' => $this->gutter,
            'margin' => $this->margin,
        ];
    }
}
