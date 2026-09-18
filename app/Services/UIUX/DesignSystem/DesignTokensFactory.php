<?php

namespace Veltrion\Services\UIUX\DesignSystem;

use Veltrion\Services\UIUX\DTOs\BrandingConfig;
use Veltrion\Services\UIUX\DTOs\DesignToken;

class DesignTokensFactory
{
    /**
     * @return array<string, DesignToken>
     */
    public function createTokens(BrandingConfig $branding): array
    {
        $tokens = [];

        // Colors
        $tokens['color-primary'] = new DesignToken('color-primary', 'color', $branding->primaryColor, null, 'Primary Brand Color');
        $tokens['color-secondary'] = new DesignToken('color-secondary', 'color', $branding->secondaryColor, null, 'Secondary Accent');
        $tokens['color-accent'] = new DesignToken('color-accent', 'color', $branding->accentColor, null, 'Highlights & CTAs');
        $tokens['color-neutral'] = new DesignToken('color-neutral', 'color', $branding->neutralColor, null, 'Neutral Dark Text');

        // Functional semantic colors
        $tokens['color-success'] = new DesignToken('color-success', 'color', '#10B981', null, 'Success State');
        $tokens['color-warning'] = new DesignToken('color-warning', 'color', '#F59E0B', null, 'Warning State');
        $tokens['color-error'] = new DesignToken('color-error', 'color', '#EF4444', null, 'Error State');
        $tokens['color-info'] = new DesignToken('color-info', 'color', '#3B82F6', null, 'Information State');

        // Surfaces
        $tokens['color-bg-light'] = new DesignToken('color-bg-light', 'color', '#F9FAFB', null, 'Light Canvas Background');
        $tokens['color-surface-light'] = new DesignToken('color-surface-light', 'color', '#FFFFFF', null, 'Light Surface / Card');
        $tokens['color-border-light'] = new DesignToken('color-border-light', 'color', '#E5E7EB', null, 'Light Divider / Border');

        $tokens['color-bg-dark'] = new DesignToken('color-bg-dark', 'color', '#111827', null, 'Dark Canvas Background');
        $tokens['color-surface-dark'] = new DesignToken('color-surface-dark', 'color', '#1F2937', null, 'Dark Surface / Card');
        $tokens['color-border-dark'] = new DesignToken('color-border-dark', 'color', '#374151', null, 'Dark Divider / Border');

        // Typography
        $tokens['font-family-base'] = new DesignToken('font-family-base', 'typography', $branding->fontFamily, null, 'Main Body & UI Font');
        $tokens['font-size-xs'] = new DesignToken('font-size-xs', 'typography', '12px', 'px', 'Extra Small');
        $tokens['font-size-sm'] = new DesignToken('font-size-sm', 'typography', '14px', 'px', 'Small / Caption');
        $tokens['font-size-base'] = new DesignToken('font-size-base', 'typography', '16px', 'px', 'Base Body Text');
        $tokens['font-size-lg'] = new DesignToken('font-size-lg', 'typography', '18px', 'px', 'Subheading');
        $tokens['font-size-xl'] = new DesignToken('font-size-xl', 'typography', '20px', 'px', 'Heading 3');
        $tokens['font-size-2xl'] = new DesignToken('font-size-2xl', 'typography', '24px', 'px', 'Heading 2');
        $tokens['font-size-3xl'] = new DesignToken('font-size-3xl', 'typography', '30px', 'px', 'Heading 1');
        $tokens['font-size-4xl'] = new DesignToken('font-size-4xl', 'typography', '36px', 'px', 'Display Hero');

        // Spacing (4px system)
        $spacings = [1 => 4, 2 => 8, 3 => 12, 4 => 16, 5 => 20, 6 => 24, 8 => 32, 10 => 40, 12 => 48, 16 => 64];
        foreach ($spacings as $key => $val) {
            $tokens["spacing-{$key}"] = new DesignToken("spacing-{$key}", 'spacing', "{$val}px", 'px', "Spacing {$val}px");
        }

        // Border Radii
        $tokens['radius-none'] = new DesignToken('radius-none', 'radius', '0px', 'px', 'No radius');
        $tokens['radius-sm'] = new DesignToken('radius-sm', 'radius', '4px', 'px', 'Small radius');
        $tokens['radius-md'] = new DesignToken('radius-md', 'radius', '8px', 'px', 'Default Medium radius');
        $tokens['radius-lg'] = new DesignToken('radius-lg', 'radius', '12px', 'px', 'Large radius (cards)');
        $tokens['radius-xl'] = new DesignToken('radius-xl', 'radius', '16px', 'px', 'Extra Large radius (modals)');
        $tokens['radius-full'] = new DesignToken('radius-full', 'radius', '9999px', 'px', 'Pill / Avatar Full radius');

        // Shadows
        $tokens['shadow-sm'] = new DesignToken('shadow-sm', 'shadow', '0 1px 2px 0 rgba(0, 0, 0, 0.05)', null, 'Subtle shadow');
        $tokens['shadow-md'] = new DesignToken('shadow-md', 'shadow', '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)', null, 'Default Card shadow');
        $tokens['shadow-lg'] = new DesignToken('shadow-lg', 'shadow', '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)', null, 'Elevated Modal shadow');

        return $tokens;
    }
}
