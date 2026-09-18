<?php

namespace Veltrion\Services\UIUX\DesignSystem;

use Veltrion\Services\UIUX\DTOs\BrandingConfig;
use Veltrion\Services\UIUX\DTOs\Theme;
use Veltrion\Services\UIUX\DTOs\DesignToken;

class ThemeManager
{
    /**
     * @param array<string, DesignToken> $globalTokens
     * @return array<string, Theme>
     */
    public function createDefaultThemes(BrandingConfig $branding, array $globalTokens): array
    {
        $light = new Theme(
            id: 'light',
            name: 'Light Theme',
            isDark: false,
            tokens: $globalTokens,
            colors: [
                'background' => '#F9FAFB',
                'surface' => '#FFFFFF',
                'text' => $branding->neutralColor,
                'border' => '#E5E7EB',
                'primary' => $branding->primaryColor,
                'secondary' => $branding->secondaryColor,
                'accent' => $branding->accentColor,
            ],
            typography: [
                'fontFamily' => $branding->fontFamily,
                'baseSize' => '16px',
            ]
        );

        $dark = new Theme(
            id: 'dark',
            name: 'Dark Theme',
            isDark: true,
            tokens: $globalTokens,
            colors: [
                'background' => '#111827',
                'surface' => '#1F2937',
                'text' => '#F3F4F6',
                'border' => '#374151',
                'primary' => $branding->primaryColor,
                'secondary' => $branding->secondaryColor,
                'accent' => $branding->accentColor,
            ],
            typography: [
                'fontFamily' => $branding->fontFamily,
                'baseSize' => '16px',
            ]
        );

        return [
            'light' => $light,
            'dark' => $dark,
        ];
    }
}
