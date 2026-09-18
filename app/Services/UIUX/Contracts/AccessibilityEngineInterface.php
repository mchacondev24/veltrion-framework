<?php

namespace Veltrion\Services\UIUX\Contracts;

use Veltrion\Services\UIUX\DTOs\ScreenModel;
use Veltrion\Services\UIUX\DTOs\DesignSystem;

interface AccessibilityEngineInterface
{
    public function auditScreen(ScreenModel $screen, DesignSystem $designSystem): array; // list of AccessibilityFinding
    public function calculateContrastRatio(string $foregroundHex, string $backgroundHex): float;
    public function passesWcagAA(string $foregroundHex, string $backgroundHex, bool $isLargeText = false): bool;
}
