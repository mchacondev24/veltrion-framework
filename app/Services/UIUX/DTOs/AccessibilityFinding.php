<?php

namespace Veltrion\Services\UIUX\DTOs;

use Veltrion\Services\UIUX\Enums\AccessibilitySeverity;

class AccessibilityFinding
{
    public function __construct(
        public readonly string $ruleId,
        public readonly AccessibilitySeverity $severity,
        public readonly string $componentId,
        public readonly string $screenId,
        public readonly string $message,
        public readonly string $remediation
    ) {}

    public function toArray(): array
    {
        return [
            'rule_id' => $this->ruleId,
            'severity' => $this->severity->value,
            'component_id' => $this->componentId,
            'screen_id' => $this->screenId,
            'message' => $this->message,
            'remediation' => $this->remediation,
        ];
    }
}
