<?php

namespace Veltrion\Services\UIUX\DesignSystem;

use Veltrion\Services\UIUX\Contracts\DesignSystemEngineInterface;
use Veltrion\Services\UIUX\DTOs\BrandingConfig;
use Veltrion\Services\UIUX\DTOs\Breakpoint;
use Veltrion\Services\UIUX\DTOs\DesignSystem;
use Veltrion\Services\UIUX\Enums\BreakpointType;

class DesignSystemEngine implements DesignSystemEngineInterface
{
    private DesignTokensFactory $tokensFactory;
    private ThemeManager $themeManager;

    public function __construct()
    {
        $this->tokensFactory = new DesignTokensFactory();
        $this->themeManager = new ThemeManager();
    }

    public function generateDesignSystem(BrandingConfig $branding, array $customTokens = []): DesignSystem
    {
        $tokens = $this->tokensFactory->createTokens($branding);
        foreach ($customTokens as $k => $tok) {
            $tokens[$k] = $tok;
        }

        $themes = $this->themeManager->createDefaultThemes($branding, $tokens);

        $breakpoints = [
            'xs' => new Breakpoint(BreakpointType::XS, 0, 4, 12, 12),
            'sm' => new Breakpoint(BreakpointType::SM, 576, 6, 16, 16),
            'md' => new Breakpoint(BreakpointType::MD, 768, 8, 20, 20),
            'lg' => new Breakpoint(BreakpointType::LG, 992, 12, 24, 24),
            'xl' => new Breakpoint(BreakpointType::XL, 1200, 12, 24, 32),
            'xxl' => new Breakpoint(BreakpointType::XXL, 1400, 12, 32, 40),
        ];

        return new DesignSystem(
            id: 'veltrion-ds-' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $branding->appName)),
            name: "{$branding->appName} Design System",
            branding: $branding,
            themes: $themes,
            breakpoints: $breakpoints,
            globalTokens: $tokens
        );
    }

    public function exportTokensJson(DesignSystem $designSystem): string
    {
        return json_encode($designSystem->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function exportCssVariables(DesignSystem $designSystem, string $themeId = 'light'): string
    {
        $css = ":root {\n";
        foreach ($designSystem->globalTokens as $name => $token) {
            $val = $token->value;
            $css .= "  --{$name}: {$val};\n";
        }
        $css .= "}\n\n";

        if (isset($designSystem->themes['dark'])) {
            $css .= "[data-theme=\"dark\"] {\n";
            $darkColors = $designSystem->themes['dark']->colors;
            foreach ($darkColors as $key => $hex) {
                $css .= "  --color-{$key}: {$hex};\n";
            }
            $css .= "}\n";
        }

        return $css;
    }

    public function exportTailwindConfig(DesignSystem $designSystem): array
    {
        $colors = [];
        foreach ($designSystem->globalTokens as $name => $token) {
            if ($token->category === 'color') {
                $colors[str_replace('color-', '', $name)] = "var(--{$name})";
            }
        }

        return [
            'theme' => [
                'extend' => [
                    'colors' => $colors,
                    'fontFamily' => [
                        'sans' => [$designSystem->branding->fontFamily],
                    ],
                ],
            ],
        ];
    }
}
