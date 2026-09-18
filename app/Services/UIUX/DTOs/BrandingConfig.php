<?php

namespace Veltrion\Services\UIUX\DTOs;

class BrandingConfig
{
    public function __construct(
        public readonly string $appName = 'Veltrion App',
        public readonly string $primaryColor = '#3B82F6',
        public readonly string $secondaryColor = '#10B981',
        public readonly string $accentColor = '#8B5CF6',
        public readonly string $neutralColor = '#1F2937',
        public readonly string $fontFamily = 'Inter, system-ui, -apple-system, sans-serif',
        public readonly ?string $logoUrl = null,
        public readonly ?string $faviconUrl = null,
        public readonly string $style = 'modern'
    ) {}

    public function toArray(): array
    {
        return [
            'app_name' => $this->appName,
            'primary_color' => $this->primaryColor,
            'secondary_color' => $this->secondaryColor,
            'accent_color' => $this->accentColor,
            'neutral_color' => $this->neutralColor,
            'font_family' => $this->fontFamily,
            'logo_url' => $this->logoUrl,
            'favicon_url' => $this->faviconUrl,
            'style' => $this->style,
        ];
    }
}
