<?php

namespace Veltrion\Services\UIUX\Accessibility;

use Veltrion\Services\UIUX\Contracts\AccessibilityEngineInterface;
use Veltrion\Services\UIUX\DTOs\AccessibilityFinding;
use Veltrion\Services\UIUX\DTOs\DesignSystem;
use Veltrion\Services\UIUX\DTOs\ScreenModel;
use Veltrion\Services\UIUX\Enums\AccessibilitySeverity;

class AccessibilityEngine implements AccessibilityEngineInterface
{
    public function auditScreen(ScreenModel $screen, DesignSystem $designSystem): array
    {
        $findings = [];

        // 1. Contrast Check between Brand Colors and Surfaces
        $bgLight = $designSystem->globalTokens['color-bg-light']->value ?? '#F9FAFB';
        $primary = $designSystem->branding->primaryColor;

        $ratio = $this->calculateContrastRatio($primary, $bgLight);
        if ($ratio < 3.0) {
            $findings[] = new AccessibilityFinding(
                ruleId: 'WCAG-1.4.3-contrast',
                severity: AccessibilitySeverity::HIGH,
                componentId: 'branding_primary',
                screenId: $screen->id,
                message: "Contrast ratio of primary color ({$primary}) against light background is {$ratio}:1 (below 3:1).",
                remediation: "Darken the primary brand color to achieve at least 4.5:1 for body text or 3.0:1 for large UI components."
            );
        }

        // 2. Component Auditing
        foreach ($screen->components as $component) {
            if ($component->name === 'Button') {
                $label = $component->properties['label']->defaultValue ?? null;
                if (empty($label) && empty($component->properties['icon']->defaultValue)) {
                    $findings[] = new AccessibilityFinding(
                        ruleId: 'WCAG-4.1.2-name',
                        severity: AccessibilitySeverity::CRITICAL,
                        componentId: $component->id,
                        screenId: $screen->id,
                        message: "Button on screen {$screen->id} is missing accessible name/label.",
                        remediation: "Add an explicit 'label' or 'aria-label' attribute."
                    );
                }
            }

            if ($component->name === 'Input') {
                $label = $component->properties['label']->defaultValue ?? null;
                if (empty($label)) {
                    $findings[] = new AccessibilityFinding(
                        ruleId: 'WCAG-3.3.2-labels',
                        severity: AccessibilitySeverity::HIGH,
                        componentId: $component->id,
                        screenId: $screen->id,
                        message: "Form input on screen {$screen->id} does not have an associated visual label.",
                        remediation: "Provide a visible <label> linked via htmlfor/id."
                    );
                }
            }
        }

        return $findings;
    }

    public function calculateContrastRatio(string $foregroundHex, string $backgroundHex): float
    {
        $l1 = $this->calculateRelativeLuminance($foregroundHex);
        $l2 = $this->calculateRelativeLuminance($backgroundHex);

        $lighter = max($l1, $l2);
        $darker = min($l1, $l2);

        return round(($lighter + 0.05) / ($darker + 0.05), 2);
    }

    public function passesWcagAA(string $foregroundHex, string $backgroundHex, bool $isLargeText = false): bool
    {
        $ratio = $this->calculateContrastRatio($foregroundHex, $backgroundHex);
        return $isLargeText ? $ratio >= 3.0 : $ratio >= 4.5;
    }

    private function calculateRelativeLuminance(string $hex): float
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        $adjust = fn(float $c) => ($c <= 0.03928) ? $c / 12.92 : pow(($c + 0.055) / 1.055, 2.4);

        $rL = $adjust($r);
        $gL = $adjust($g);
        $bL = $adjust($b);

        return 0.2126 * $rL + 0.7152 * $gL + 0.0722 * $bL;
    }
}
