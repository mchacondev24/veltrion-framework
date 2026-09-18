<?php

namespace Veltrion\Services\UIUX\DTOs;

use Veltrion\Services\UIUX\Enums\AccessibilitySeverity;

class AccessibilityRule
{
    public function __construct(
        public readonly string $id, // e.g. WCAG-1.4.3-contrast, WCAG-1.1.1-alt, WCAG-2.4.7-focus
        public readonly string $wcagCriterion,
        public readonly string $level, // A, AA, AAA
        public readonly AccessibilitySeverity $severity,
        public readonly string $description,
        public readonly string $recommendation
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'wcag_criterion' => $this->wcagCriterion,
            'level' => $this->level,
            'severity' => $this->severity->value,
            'description' => $this->description,
            'recommendation' => $this->recommendation,
        ];
    }
}
