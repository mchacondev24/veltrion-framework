<?php

namespace Veltrion\Services\UIUX\Contracts;

use Veltrion\Services\UIUX\DTOs\BrandingConfig;
use Veltrion\Services\UIUX\DTOs\DesignSystem;

interface DesignSystemEngineInterface
{
    public function generateDesignSystem(BrandingConfig $branding, array $customTokens = []): DesignSystem;
    public function exportTokensJson(DesignSystem $designSystem): string;
    public function exportCssVariables(DesignSystem $designSystem, string $themeId = 'light'): string;
    public function exportTailwindConfig(DesignSystem $designSystem): array;
}
